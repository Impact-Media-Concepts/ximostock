<?php

namespace App;

use App\Models\Category;
use App\Models\CategoryProductSalesChannel;
use App\Models\CategorySalesChannel;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\ProductSalesChannelProperty;
use App\Models\Property;
use App\Models\PropertySalesChannel;
use App\Models\SalesChannel;
use App\View\Components\properties;
use Automattic\WooCommerce\Client;

use Exception;
use PDO;

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

    public function deleteProductsFromAll(array $productIds)
    {
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
        foreach ($results as $salesChannelId => $productIds) {
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

    public function uploadOrUpdateProductsSalesChannel($products, SalesChannel $salesChannel) //big upload fucntion
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);

        $productIds = $products->pluck('id')->toArray();
        $productSalesChannels = ProductSalesChannel::whereIn('product_id', $productIds)->where('sales_channel_id', $salesChannel->id)->get();

        $data = [
            'create' => [],
            'update' => []
        ];
        //prepare categories and poperties
        $categories = [];
        $properties = [];
        $failedToUpdate = [];
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
            //add the  saleschannel specific as well
            $productSalesChannel = $productSalesChannels->where('product_id', $product->id)->first();
            if ($productSalesChannel) {
                foreach ($productSalesChannel->categories as $category) {
                    if (!in_array($category->id, $categories)) {
                        array_push($categories, $category->id);
                    }
                }
                foreach ($productSalesChannel->properties as $property) {
                    if (!in_array($property->id, $categories)) {
                        array_push($properties, $property->id);
                    }
                }
            }
        }

        $this->uploadCategoriesToSalesChannel($categories, $salesChannel, $woocommerce);
        $this->uploadProperties($properties, $salesChannel, $woocommerce);

        #region //data for products (less database queries)
        $categorySalesChannels =  CategorySalesChannel::whereIn('category_id', $categories)->where('sales_channel_id', $salesChannel->id)->get();
        $propertySalesChannels = PropertySalesChannel::whereIn('property_id', $properties)->where('sales_channel_id', $salesChannel->id)->get();
        $productProperties = ProductProperty::whereIn('product_id', $productIds)->get();
        #endregion

        //prepare data
        foreach ($products as $index => $product) {
            $update = $productSalesChannels->where('product_id', $product->id)->whereNotNull('external_id')->first();

            // if the product is presend add it to be updated. if it is not add it to be created
            $productData = $this->prepareProductData($product, $productSalesChannels, $categorySalesChannels, $propertySalesChannels, $productProperties,);

            if ($update) {
                $productData += ['id' => $update->external_id];
                array_push($data['update'], $productData);
            } else {
                array_push($data['create'], $productData);
            }

            $requestCount = Count($data['create']) + Count($data['update']);
            if ($requestCount >= 100 || $index === count($products) - 1) {

                $results = $woocommerce->post('products/batch', $data);

                //create external ids for created producst
                if (Count($data['create'])) {
                    $this->setExternalIds($results, $products, $salesChannel);
                }
                //handle errors for updated products
                if (isset($results->update)) {
                    foreach ($results->update as $result) {
                        if (isset($result->error)) {
                            $failedData = array_filter($data['update'], function ($product) use ($result) {
                                return $product['id'] == $result->id;
                            });
                            array_push($failedToUpdate, reset($failedData));
                        }
                    }
                    //dd here
                    if (count($failedToUpdate)) {
                        //remove the id from the products so i can create them instead of updating
                        $failedToUpdate = array_map(function ($product) {
                            unset($product['id']);
                            return $product;
                        }, $failedToUpdate);
                        //cut the data into chunks for the api to handle (limit 100)
                        $batches = array_chunk($failedToUpdate, 100);
                        foreach ($batches as $batch) {
                            $data = ['create' => $batch];
                            $results = $woocommerce->post('products/batch', $data);
                            if ($results) {
                                $this->setExternalIds($results, $products, $salesChannel);
                            }
                        }
                    }
                }
                //reset data for next batch
                $data = [
                    'create' => [],
                    'update' => []
                ];
            }
        }
    }

    protected function setExternalIds($results, $products, $salesChannel)
    {
        foreach ($results->create as $result) {
            if (!isset($result->error)) {
                $product = $products->first(function ($product) use ($result) {
                    if (!isset($product->sku) || !isset($result->sku)) { //dd for bugtesting
                        dd($product, $result);
                    }
                    return $product->sku == $result->sku;
                });
                $productSalesChannel = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                $productSalesChannel->external_id = $result->id;
                $productSalesChannel->save();
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
                if (!isset($result->error)) {
                    $propertyName = $result->name;
                    $property = $properties->first(function ($property) use ($propertyName) {
                        return $property->name == $propertyName;
                    });
                    PropertySalesChannel::create([
                        'sales_channel_id' => $salesChannel->id,
                        'property_id' => $property->id,
                        'external_id' => $result->id
                    ]);
                } else {
                    //todo slug in use . get id and save as external id.
                }
            }
        }
    }

    //check if theree are any differences between the sales channel and the ximostock database and if so. correct these differences.
    protected function overrideWoocommerce()
    {
    }

    #region // prepare product data
    protected function prepareProductData(Product $product, $productSalesChannels, $categorySalesChannels, $propertySalesChannels, $productProperties)
    {
        $productSalesChannel = $productSalesChannels->where('product_id', $product->id)->first();

        //prepare product categories
        $categories = $this->prepareProductCategoryData($product, $productSalesChannel, $categorySalesChannels);
        //prepare product properies
        $properties = $this->prepareProductPropertyData($product, $productSalesChannel, $propertySalesChannels, $productProperties);

        //prepare final product
        $productData = [
            'name' => isset($productSalesChannel->title) ? $productSalesChannel->title : $product->title,
            'type' => 'simple',
            'regular_price' => isset($productSalesChannel->price) ? $productSalesChannel->price : $product->price,
            'sale_price' => isset($productSalesChannel->discount) ? ($productSalesChannel->discount) : ($product->discount ? $product->discount : ''), //if discount is null set value empty string. WIP
            'description' =>  isset($productSalesChannel->long_description) ? $productSalesChannel->long_description : $product->long_description,
            'short_description' => isset($productSalesChannel->short_description) ? $productSalesChannel->short_description : $product->short_description,
            'sku' => isset($productSalesChannel->sku) ? $productSalesChannel->sku : $product->sku,
            'categories' => $categories,
            'attributes' => $properties,
            'meta_data' => [
                [
                    'key' => '_ean_code',
                    'value' => isset($productSalesChannel->ean) ? $productSalesChannel->ean : $product->ean
                ]
            ]
        ];
        return $productData;
    }

    protected function prepareProductCategoryData(Product $product, ProductSalesChannel $productSalesChannel, $categorySalesChannels)
    {
        $categoryIds = CategoryProductSalesChannel::where('product_sales_channel_id', $productSalesChannel->id)->get()->pluck('category_id')->toArray();

        if (Count($categoryIds)) {
            $categoryIds = CategorySalesChannel::whereIn('category_id', $categoryIds)->get()->pluck('external_id')->toArray();
            $categories = [];
            foreach ($categoryIds as $categoryId) {
                array_push($categories, ['id' => $categoryId]);
            }
            return $categories;
        } else {
            //prepare product categories
            $categoryIds = $product->categories->pluck('id')->toArray();
            $categoryIds = $categorySalesChannels->whereIn('category_id', $categoryIds)->pluck('external_id')->toArray();
            $categories = [];
            foreach ($categoryIds as $category) {
                array_push($categories, ['id' => $category]);
            }
            return $categories;
        }
    }

    protected function prepareProductPropertyData(Product $product, ProductSalesChannel $productSalesChannel, $propertySalesChannels, $productProperties)
    {
        $proppertyIds = ProductSalesChannelProperty::where('product_sales_channel_id', $productSalesChannel->id)->get()->pluck('property_id')->toArray();

        if (Count($proppertyIds)) {
            $propertyIds = PropertySalesChannel::whereIn('property_id', $proppertyIds)->get()->pluck('external_id')->toArray();
            $properties = [];
            foreach ($propertyIds as $propertyId) {
                $values = $productProperties->where('external_id', $propertyId)->where('product_id', $product->id)->first();
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
                    'id' => $propertyId,
                    'options' => $options
                ];
                array_push($properties, $propData);
            }
            return $properties;
        } else {
            //prepare product properies
            $propertyIds = $product->properties->pluck('id')->toArray();
            $propertyIds = $propertySalesChannels->whereIn('property_id', $propertyIds)->pluck('property_id', 'external_id')->toArray();
            $properties = [];

            foreach ($propertyIds as $externalId => $propertyId) {
                $values = $productProperties->where('property_id', $propertyId)->where('product_id', $product->id)->first();
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
            return $properties;
        }
    }
    #endregion
}
