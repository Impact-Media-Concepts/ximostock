<?php

namespace App;

use App\Models\Category;
use App\Models\CategorySalesChannel;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\PropertySalesChannel;
use App\Models\SalesChannel;
use Automattic\WooCommerce\Client;
use Carbon\Carbon;
use Exception;
use PDO;
use PHPUnit\Framework\Constraint\Count;

class WooCommerceManager
{
    public function __construct()
    {
        //
    }

    protected function createSalesChannelsClient(SalesChannel $salesChannel): Client|null
    {
        try {
            $woocommerce = new Client(
                $salesChannel->url,
                $salesChannel->api_key,
                $salesChannel->secret,
                [
                    'timeout' => 30
                ]
            );
            return $woocommerce;
        } catch (Exception $ex) {
            return null;
        }
    }

    protected function productOnSalesChannel(Product $product, SalesChannel $salesChannel): bool
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $external_id = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
        if ($external_id != null) {
            $external_id = $external_id->external_id;
            $OnlineProduct = $woocommerce->get('products/' . $external_id);
            return $OnlineProduct != null;
        } else {
            return false;
        }
    }

    public function deleteProductsFromAll(array $productIds){
        $productSalesChannels = ProductSalesChannel::whereIn('product_id', $productIds)->get();

        // Initialize an empty array to store the result
        $results = [];
    
        // Organize the data into an array where sales channel IDs are keys and product IDs are values
        foreach ($productSalesChannels as $productSalesChannel) {
            $salesChannelId = $productSalesChannel->sales_channel_id;
            $productId = $productSalesChannel->product_id;
    
            // Create a new array element if it doesn't exist
            if (!isset($results[$salesChannelId])) {
                $results[$salesChannelId] = [];
            }
    
            // Add the product ID to the array of product IDs for the sales channel
            $results[$salesChannelId][] = $productId;
        }
        foreach($results as $salesChannelId => $productIds){
            $salesChannel = SalesChannel::findOrFail($salesChannelId);
            $this->deleteProductsFromSalesChannel($productIds, $salesChannel);
        }
    }

    public function deleteProductsFromSalesChannel(array $productIds, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $externalIds = ProductSalesChannel::whereIn('product_id', $productIds)
        ->where('sales_channel_id', $salesChannel->id)
        ->pluck('external_id')
        ->toArray();
        $batches = array_chunk($externalIds, 100);
        foreach ($batches as $batch) {
            $data = ['delete' => $batch];
           $woocommerce->post('products/batch', $data);
        }
    }



    public function uploadOrUpdateProductsSalesChannel($products, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        //todo check for simple or variant product
        $data = [
            'create' => [],
            'update' => []
        ];

        //prepare categories and poperties
        $categories = [];
        $properties = [];

        foreach ($products as $product) {
            foreach ($product->categories as $category) {
                if (!in_array($category->id, $categories)) {
                    array_push($categories, $category->id);
                }
            }
            foreach ($product->properties as $property) {
                if (!in_array($property->id, $categories)) {
                    array_push($properties, $property->id);
                }
            }
        }

        $this->uploadCategoriesToSalesChannel($categories, $salesChannel, $woocommerce);
        $this->uploadProperties($properties, $salesChannel, $woocommerce);

        //prepare data
        foreach ($products as $index => $product) {
            $update = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->whereNotNull('external_id')->get()->first();
            //prepare category data
            $categoryIds = $product->categories->pluck('id')->toArray();
            $categoryIds = CategorySalesChannel::whereIn('category_id', $categoryIds)->get()->pluck('external_id')->toArray();
            $categories = [];
            foreach ($categoryIds as $categoryId) {
                array_push($categories, ['id' => $categoryId]);
            }
            //prepare property data
            $propertyIds = $product->properties->pluck('id')->toArray();
            $propertyIds = PropertySalesChannel::whereIn('property_id', $propertyIds)->where('sales_channel_id', $salesChannel->id)->get()->pluck('property_id', 'external_id')->toArray();

            $properties = [];
            foreach ($propertyIds as $externalId => $propertyId) {
                $values = ProductProperty::where('property_id', $propertyId)->where('product_id', $product->id)->get()->first();
                $values = (array)json_decode($values->property_value)->value;
                $options = [];
                foreach ($values as $value) {
                    if (gettype($value) === 'string') {
                        array_push($options, $value);
                    } else {
                        array_push($options, json_encode($value));
                    }
                }
                $propData = [
                    'id' => $externalId,
                    'options' => $options
                ];
                array_push($properties, $propData);
            }
            //prepeare product data 
            $productData = [
                'name' => $product->title,
                'type' => 'simple',
                'regular_price' => $product->price,
                'sale_price' => $product->discount ? $product->discount : '', //if discount is null set value empty string
                'description' => $product->long_descrition,
                'short_description' => $product->short_description,
                'sku' => $product->sku,
                'categories' => $categories,
                'attributes' => $properties,
                'meta_data' => [
                    [
                        'key' => '_ean_code',
                        'value' => $product->ean
                    ]
                ]
            ];
            // if the product is presend add it to be updated. if it is not add it to be created
            if ($update) {
                $productData += ['id' => $update->external_id];
                array_push($data['update'], $productData);
            } else {
                array_push($data['create'], $productData);
            }

            $requestCount = Count($data['create']) + Count($data['update']);
            if ($requestCount >= 100 || $index === count($products) - 1) {
                $results = $woocommerce->post('products/batch', $data);
                if (Count($data['create'])) {
                    foreach ($results->create as $result) {
                        $product = $products->first(function ($product) use ($result) {
                            return $product->sku == $result->sku;
                        });
                        $productSalesChannel = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                        $productSalesChannel->external_id = $result->id;
                        $productSalesChannel->save();
                    }
                }
                $data = [
                    'create' => [],
                    'update' => []
                ];
            }
        }
    }

    //upload all given categories based on id.
    protected function uploadCategoriesToSalesChannel(array $categoryIds, SalesChannel $salesChannel, Client $woocommerce)
    {
        $categories = array_diff($categoryIds, $salesChannel->categories->pluck('id')->toArray());
        $categories = Category::whereIn('id', $categories)->get();

        foreach ($categories as $category) {
            if (!CategorySalesChannel::where('category_id', '=', $category->id)->where('sales_channel_id', '=', $salesChannel->id)->exists()) {
                if ($category->parent_category_id != null) {
                    $parent = CategorySalesChannel::where('category_id', '=', $category->parent_category_id)->where('sales_channel_id', '=', $salesChannel->id)->get()->first();
                    if ($parent != null) //check if the parent is uploaded
                    {
                        $data = [
                            'name' => $category->name,
                            'parent' => $parent->external_id
                        ];
                        $response = $woocommerce->post('products/categories', $data);
                        CategorySalesChannel::create([
                            'category_id' => $category->id,
                            'sales_channel_id' => $salesChannel->id,
                            'external_id' => $response->id
                        ]);
                    } else {
                        $this->UploadParentCategoryRecursive(Category::where('id', $category->parent_category_id)->get()->first(), $salesChannel);
                    }
                } else {
                    $data = [
                        'name' => $category->name
                    ];
                    $response = $woocommerce->post('products/categories', $data);
                    CategorySalesChannel::create([
                        'category_id' => $category->id,
                        'sales_channel_id' => $salesChannel->id,
                        'external_id' => $response->id
                    ]);
                }
            }
        }
    }

    protected function UploadParentCategoryRecursive(Category $category, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);

        if (!CategorySalesChannel::where('category_id', '=', $category->id)->where('sales_channel_id', '=', $salesChannel->id)->exists()) {
            if ($category->parent_category_id != null) {
                $parent = CategorySalesChannel::where('category_id', '=', $category->parent_category_id)->where('sales_channel_id', '=', $salesChannel->id)->get();
                if ($parent != null) //check if the parent is uploaded
                {
                    $data = [
                        'name' => $category->name,
                        'parent' => $parent->external_id
                    ];
                    $response = $woocommerce->post('products/categories', $data);
                    CategorySalesChannel::create([
                        'category_id' => $category->id,
                        'sales_channel_id' => $salesChannel->id,
                        'extrenal_id' => $response->id
                    ]);
                } else {
                    $this->UploadParentCategoryRecursive(Category::where('id', $category->parent_category_id)->get()->first(), $salesChannel);
                }
            } else {
                $data = [
                    'name' => $category->name
                ];
                $response = $woocommerce->post('products/categories', $data);
                CategorySalesChannel::create([
                    'category_id' => $category->id,
                    'sales_channel_id' => $salesChannel->id,
                    'extrenal_id' => $response->id
                ]);
            }
        }
    }

    protected function uploadProperties(array $propertyIds, SalesChannel $salesChannel, Client $woocommerce)
    {

        $properties = array_diff($propertyIds, $salesChannel->properties->pluck('id')->toArray());
        $properties = Property::whereIn('id', $properties)->get();
        if (Count($properties)) {
            $data = [
                'create' => [],
            ];
            foreach ($properties as $property) {
                array_push($data['create'], ['name' => $property->name]);
            }
            $results = $woocommerce->post('products/attributes/batch', $data);
            foreach ($results->create as $result) {
                $propertyName = $result->name;
                $property = $properties->first(function ($property) use ($propertyName) {
                    return $property->name == $propertyName;
                });
                PropertySalesChannel::create([
                    'sales_channel_id' => $salesChannel->id,
                    'property_id' => $property->id,
                    'external_id' => $result->id
                ]);
            }
        }
    }

    //check if theree are any differences between the sales channel and the ximostock database and if so. correct these differences.
    protected function overrideWoocommerce()
    {
    }
}
