<?php

namespace App;

use App\Helpers\CategoryHelper;
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
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Auth;

use Exception;
use PhpOffice\PhpSpreadsheet\Chart\Properties;


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
                    'wp_api' => true,
                    'version' => 'wc/v3',
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

    public function deleteCategoriesFromSaleschannel(array $categoryIds, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $categoryIds = CategoryHelper::getAllCategoryIdsWithChildren($categoryIds);
        $externalIds = CategoryHelper::getExternalIdsForCategories($categoryIds, $salesChannel->id);
        $data = ['delete' => $externalIds];

        $woocommerce->post('products/categories/batch', $data);
    }

    public function deletePropertiesFromSaleschannel(array $propertyIds, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $externalIds = PropertySalesChannel::whereIn('product_id', $propertyIds)->where('sales_channel_id', $salesChannel->id)->pluck('external_id')->toArray();
        $data = ['delete'=> $externalIds];
        $woocommerce->post('products/attributes/batch', $data);
    }

    public function deleteProperties(array $propertyIds)
    {
        $saleschannels = SalesChannel::Where('work_space_id', Auth::user()->work_space_id)->get();

        foreach ($saleschannels as $salesChannel) {
            $woocommerce = $this->createSalesChannelsClient($salesChannel);
            $externalIds = PropertySalesChannel::whereIn('property_id', $propertyIds)->where('sales_channel_id', $salesChannel->id)->get()->pluck('external_id')->toArray();
            if(Count($externalIds)){
                $data = ['delete'=> $externalIds];
                $woocommerce->post('products/attributes/batch', $data);
            }
        }
    }

    public function deleteCategoriesFromSalesChannels(array $categoryIds)
    {
        $categoryIds = CategoryHelper::getAllCategoryIdsWithChildren($categoryIds);
        $externalIdsPerSaleschannel = CategoryHelper::getExternalIdsGroupedBySalesChannel($categoryIds);
        foreach ($externalIdsPerSaleschannel as $saleschannelId => $externalIds) {
            $data = ['delete' => $externalIds];
            $saleschannel = Saleschannel::findOrFail($saleschannelId);
            $woocommerce = $this->createSalesChannelsClient($saleschannel);
            $woocommerce->post('products/categories/batch', $data);
        }
    }


    public function uploadOrUpdateProductsSalesChannel($products, SalesChannel $salesChannel) //big upload fucntion
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);

        $productIds = $products->pluck('id')->toArray();
        $productSalesChannels = ProductSalesChannel::whereIn('product_id', $productIds)->where('sales_channel_id', $salesChannel->id)->with('properties', 'categories')->get();

        $data = [
            'create' => [],
            'update' => []
        ];
        #region //prepare categories and poperties
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
                if (!in_array($property->id, $properties)) {
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
                    if (!in_array($property->id, $properties)) {
                        array_push($properties, $property->id);
                    }
                }
            }
        }

        $this->uploadCategoriesToSalesChannel($categories, $salesChannel, $woocommerce);
        $this->uploadProperties($properties, $salesChannel, $woocommerce);

        #endregion

        $categorySalesChannels = CategorySalesChannel::whereIn('category_id', $categories)->where('sales_channel_id', $salesChannel->id)->get();
        $propertySalesChannels = PropertySalesChannel::whereIn('property_id', $properties)->where('sales_channel_id', $salesChannel->id)->get();
        $productProperties = ProductProperty::whereIn('product_id', $productIds)->get();
        $categoryProductSalesChannels = CategoryProductSalesChannel::where('product_sales_channel_id', $productSalesChannel->id)->get();
        $propertyProductSaleschannels = ProductSalesChannelProperty::where('product_sales_channel_id', $productSalesChannel->id)->get();
        //prepare data
        foreach ($products as $index => $product) {
            $update = $productSalesChannels->where('product_id', $product->id)->whereNotNull('external_id')->first();

            // if the product is presend add it to be updated. if it is not add it to be created
            $productData = $this->prepareProductData($product, $productSalesChannels, $categorySalesChannels, $propertySalesChannels, $productProperties, $categoryProductSalesChannels, $propertyProductSaleschannels);

            if ($update) {
                $productData += ['id' => $update->external_id];
                array_push($data['update'], $productData);
            } else {
                array_push($data['create'], $productData);
            }

            $requestCount = Count($data['create']) + Count($data['update']);
            if ($requestCount >= 100 || $index === count($products) - 1) {

                $results = $woocommerce->post('products/batch', $data);
                //dd($results, $data);
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
                    if (count($failedToUpdate)) {
                        //remove the id from the products so i can create them instead of updating
                        $failedToUpdate = array_map(function ($product) {
                            unset ($product['id']);
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
            if (!isset($result->error) && isset($result->id)) {
                $product = $products->first(function ($product) use ($result) {
                    return $product->sku == $result->sku;
                });
                $productSalesChannel = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                $productSalesChannel->update([
                    'external_id' => $result->id
                ]);
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
                            'slug' => env('XS_PREFIX', 'xs_') . $category->name,
                            'parent' => $parent->external_id
                        ];

                        $response = $woocommerce->post('products/categories', $data);
                        CategorySalesChannel::create([
                            'category_id' => $category->id,
                            'sales_channel_id' => $salesChannel->id,
                            'external_id' => $response->id
                        ]);
                    } else {
                        $this->UploadParentCategoryRecursive(Category::where('id', $category->parent_category_id)->get()->first(), $salesChannel, $woocommerce);
                    }
                } else {
                    $data = [
                        'name' => $category->name,
                        'slug' => env('XS_PREFIX', 'xs_') . $category->name
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
        // get all categories that shoeld be uploaded and find out witch ones are not.
        $categories = CategorySalesChannel::whereIn('category_id', $categoryIds)->where('sales_channel_id', $salesChannel->id)->get();
        $categoryIds = $categories->pluck('external_id')->toArray();
        $results = $woocommerce->get('products/categories', ['include' => $categoryIds]);
        $externalCategories = [];
        foreach ($results as $result) {
            array_push($externalCategories, $result->id);
        }
        $missingCategories = $categories->reject(function ($category) use ($externalCategories) {
            return in_array($category->external_id, $externalCategories);
        });
        //upload the missing categories;
        foreach ($missingCategories as $categorySaleschannel) {
            $parentCategory = $categorySaleschannel->parent_category;
            $category = Category::findOrFail($categorySaleschannel->category_id);
            if ($parentCategory) {
                if ($missingCategories->contains($parentCategory)) {
                    //first opload parent recursiv then upload this.
                } else {
                    $parentExternalId = CategorySalesChannel::where('category_id', $parentCategory->id)->where('sales_channel_id', $salesChannel->id)->get()->first()->external_id;

                    $data = [
                        'name' => $category->name,
                        'slug' => env('XS_PREFIX', 'xs_') . $category->name,
                        'parent' => $parentExternalId
                    ];
                    $woocommerce->post('products/categories', $data);
                }
            } else {
                $data = [
                    'name' => $category->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $category->name
                ];
                $result = $woocommerce->post('products/categories', $data);
                $categorySaleschannel->update([
                    'external_id' => $result->id
                ]);
            }
        }
    }

    protected function UploadParentCategoryRecursive(Category $category, SalesChannel $salesChannel, $woocommerce)
    {
        if (!CategorySalesChannel::where('category_id', '=', $category->id)->where('sales_channel_id', '=', $salesChannel->id)->exists()) {
            if ($category->parent_category_id != null) {
                $parent = CategorySalesChannel::where('category_id', '=', $category->parent_category_id)->where('sales_channel_id', '=', $salesChannel->id)->get();
                if ($parent != null) //check if the parent is uploaded
                {
                    $data = [
                        'name' => $category->name,
                        'slug' => env('XS_PREFIX', 'xs_') . $category->name,
                        'parent' => $parent->external_id
                    ];
                    $response = $woocommerce->post('products/categories', $data);
                    CategorySalesChannel::create([
                        'category_id' => $category->id,
                        'sales_channel_id' => $salesChannel->id,
                        'extrenal_id' => $response->id
                    ]);
                } else {
                    $this->UploadParentCategoryRecursive(Category::where('id', $category->parent_category_id)->get()->first(), $salesChannel, $woocommerce);
                }
            } else {
                $data = [
                    'name' => $category->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $category->name
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
        //prepare data
        $createProperties = array_diff($propertyIds, $salesChannel->properties->pluck('id')->toArray());
        $createProperties = Property::whereIn('id', $createProperties)->get();
        $updateProperties = $salesChannel->properties->pluck('id')->toArray();
        $updateProperties = Property::whereIn('id', $updateProperties)->get();


        // //update properties in saleschnanels if any
        if (Count($updateProperties)) {
            foreach ($updateProperties as $property) {
                // $external_id = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                // $external_id = $external_id->external_id;
                $data = [
                    'create' => [
                        [
                            'name' => $property->name,
                            'slug' => env('XS_PREFIX', 'xs_') . $property->name
                        ]
                    ]
                ];
                $result = $woocommerce->post('products/attributes/batch', $data);
                $result = reset($result);
                $result = reset($result);//must do this twice for reasons

                if (!isset($result->error)) {
                    if (isset($result->id)) {
                        $propSaleschannel = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                        $propSaleschannel->update([
                            'external_id' => $result->id
                        ]);
                    }
                }
            }
        }
        //create proeprties in saleschannel if any
        if (Count($createProperties)) {
            $data = [
                'create' => [],
            ];
            foreach ($createProperties as $property) {
                array_push(
                    $data['create'],
                    [
                        'name' => $property->name,
                        'slug' => env('XS_PREFIX', 'xs_') . $property->name
                    ]
                );
            }
            $results = $woocommerce->post('products/attributes/batch', $data);
            foreach ($results->create as $result) {
                if (!isset($result->error)) {
                    $propertyName = $result->name;
                    $property = $createProperties->first(function ($property) use ($propertyName) {
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
    }


    #region // prepare product data
    protected function prepareProductData(Product $product, $productSalesChannels, $categorySalesChannels, $propertySalesChannels, $productProperties, $categoryProductSalesChannels, $propertyProductSaleschannels)
    {
        $productSalesChannel = $productSalesChannels->where('product_id', $product->id)->first();

        //prepare product categories
        $categories = $this->prepareProductCategoryData($product, $productSalesChannel, $categorySalesChannels, $categoryProductSalesChannels);
        //prepare product properies
        $properties = $this->prepareProductPropertyData($product, $productSalesChannel, $propertySalesChannels, $productProperties, $propertyProductSaleschannels);

        //prepare final product
        $productData = [
            'name' => isset($productSalesChannel->title) ? $productSalesChannel->title : $product->title,
            'type' => 'simple',
            'regular_price' => isset($productSalesChannel->price) ? $productSalesChannel->price : $product->price,
            'sale_price' => isset($productSalesChannel->discount) ? ($productSalesChannel->discount) : ($product->discount ? $product->discount : ''), //if discount is null set value empty string. WIP
            'description' => isset($productSalesChannel->long_description) ? $productSalesChannel->long_description : $product->long_description,
            'short_description' => isset($productSalesChannel->short_description) ? $productSalesChannel->short_description : $product->short_description,
            'sku' => isset($productSalesChannel->sku) ? $productSalesChannel->sku : $product->sku,
            'categories' => $categories,
            'attributes' => $properties,
            'images' => $this->prepareImageData($product) != [] ? $this->prepareImageData($product) : null,
            'meta_data' => [
                [
                    'key' => '_ean_code',
                    'value' => isset($productSalesChannel->ean) ? $productSalesChannel->ean : $product->ean
                ]
            ]
        ];
        return $productData;
    }

    protected function prepareProductCategoryData(Product $product, ProductSalesChannel $productSalesChannel, $categorySalesChannels, $categoryProductSalesChannels)
    {
        $categoryIds = $categoryProductSalesChannels->where('product_sales_channel_id', $productSalesChannel->id)->pluck('category_id')->toArray();

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

    protected function prepareProductPropertyData(Product $product, ProductSalesChannel $productSalesChannel, $propertySalesChannels, $productProperties, $propertyProductSaleschannels)
    {
        $propertyIds = $propertyProductSaleschannels->where('product_sales_channel_id', $productSalesChannel->id)->pluck('property_id')->toArray();

        if (Count($propertyIds)) {
            $propertyIds = $propertySalesChannels->whereIn('property_id', $propertyIds)->pluck('property_id', 'external_id')->toArray();
            $properties = [];
            foreach ($propertyIds as $externalId => $propertyId) {
                //get values
                $values = ProductSalesChannelProperty::where('property_id', $propertyId)->get()->first();
                $values = (array) json_decode($values->prop_value)->value;

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
        } else {
            //prepare product properies
            $propertyIds = $product->properties->pluck('id')->toArray();
            $propertyIds = $propertySalesChannels->whereIn('property_id', $propertyIds)->pluck('property_id', 'external_id')->toArray();
            $properties = [];

            foreach ($propertyIds as $externalId => $propertyId) {
                $values = $productProperties->where('property_id', $propertyId)->where('product_id', $product->id)->first();
                $values = (array) json_decode($values->property_value)->value;
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

    protected function prepareImageData(Product $product)
    {
        $photoData = [];
        foreach ($product->photos as $photos) {
            $data = ['src' => $photos->url];
            array_push($photoData, $data);
        }
        return $photoData;
    }
    #endregion
}