<?php

namespace App;
use App\Models\Category;
use App\Models\CategorySalesChannel;
use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\ProductProperty;
use App\Models\PropertySalesChannel;
use App\Models\ProductSalesChannelProperty;
use App\Models\CategoryProductSalesChannel;
use App\Models\SalesChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Automattic\WooCommerce\Client;
use Exception;

class SalesChannelManager
{
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

    //Upload products to a given saleschannel (woocommerce)
    public function uploadProductsToSaleschannel(Collection $products, SalesChannel $salesChannel)
    {
        //collect from all products the data of what needs to happen.
        $SimpleProductsToCreate = [];
        $VariableProductsToCreate = [];
        $SimpleProductsToUpdate = [];
        $VariableProductsToUpdate = [];
        foreach ($products as $product) { //deel de produtcen correct in
            if($product->type == 'simple'){
                if($this->productIsLinked($product, $salesChannel)){
                    array_push($SimpleProductsToUpdate, $product->id);
                }else{
                    array_push($SimpleProductsToCreate, $product->id);
                }
            }else{
                if($this->productIsLinked($product, $salesChannel)){
                    array_push($VariableProductsToUpdate, $product->id);
                }else{
                    array_push($VariableProductsToCreate, $product->id);
                }
            }
        }
        //create a connection to the saleschannel
        $woocommmerce = $this->createSalesChannelsClient($salesChannel);

        $productIds = [];
        foreach ($products as $product) {
            array_push($productIds, $product->id);
            foreach ($product->childProducts as $childProduct) {
                array_push($productIds, $childProduct->id);
            }
        }

        $properties = Property::whereIn('id', ProductProperty::whereIn('product_id', $productIds)->pluck('property_id'))->get();
        Log::info('properties to upload');
        Log::info(json_encode($properties));
        $this->uploadPropertiesToSaleschannel($properties, $salesChannel, $woocommmerce);

        //upload or update categories of products
        $categories = $products->flatMap(function ($product) {
            return $product->categories->pluck('id');
        })->unique()->toArray();
        $categories = Category::whereIn("id", $categories)->get();
        $this->uploadCategoriesToSalesChannel($categories, $salesChannel, $woocommmerce);

        //upload new products
        $SimpleProductsToCreate = Product::whereIn('id', $SimpleProductsToCreate)->get(); //get the products
        $this->uploadSimpleProducts($SimpleProductsToCreate, $salesChannel, $woocommmerce);

        //update existing products
        $SimpleProductsToUpdate = Product::whereIn('id', $SimpleProductsToUpdate)->get(); //get the products
        $this->updateSimpleProducts($SimpleProductsToUpdate, $salesChannel, $woocommmerce);

        //upload new variable products
        Log::info('variable products');
        Log::info(json_encode($VariableProductsToCreate));
        $VariableProductsToCreate = Product::whereIn('id', $VariableProductsToCreate)->get(); //get the products
        $this->uploadVariableProducts($VariableProductsToCreate, $salesChannel, $woocommmerce);
        Log::info('great success');
    }

    #region Products

    //if a product is already linked to a saleschannel return false. (being linked means an entry in the pivot table exits. it is not jet live on the saleschannel)
    //Otherwise create a link and return true
    protected function productIsLinked(Product $product, SalesChannel $salesChannel)
    {
        if($product->salesChannels->contains($salesChannel)){
            return true;
        }else{
            ProductSalesChannel::create([
                'product_id' => $product->id,
                'sales_channel_id' => $salesChannel->id
            ]);
            return false;
        }
    }

    protected function uploadSimpleProducts(Collection $products, SalesChannel $salesChannel, Client $woocommerce)
    {
        $current_workspace = (int) session('active_workspace_id');
        Log::info('uploading simple products');
        Log::info($products);
        $productBatchs = $products->chunk(100);
        try{
            foreach ($productBatchs as $productBatch) {
                $data = ['create' => []];
                foreach($productBatch as $product){//prepare data
                    $productLink = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first();
                    //prepare properties
                    $properties = $this->prepareProductPropertyData($product, $salesChannel); //null reference
                    //prepare categories
                    $categories = $this->prepareProductCategoryData($product, $productLink);

                    $productData = [
                        'name' => isset($productLink->title) ? $productSalesChannel->title : $product->title,
                        'short_description' => isset($productLink->short_description) ? $productSalesChannel->short_description : $product->short_description,
                        'sku' => isset($productLink->sku) ? $productSalesChannel->sku : $product->sku,
                        'type' => 'simple',
                        'regular_price' => isset($productLink->price) ? $productSalesChannel->price : $product->price,
                        'sale_price' => isset($productLink->discount) ? ($productSalesChannel->discount) : ($product->discount ? $product->discount : ''), //if discount is null set value empty string. WIP
                        'description' => isset($productLink->long_description) ? $productSalesChannel->long_description : $product->long_description,
                        'categories' => $categories,
                        'attributes' => $properties,
                        'manage_stock' => $product->communicate_stock,
                        'stock_quantity' => $product->stock,
                        'backorders' => $product->backorders ? 'yes' : 'no',
                        'meta_data' => [
                            [
                                'key' => '_ean_code',
                                'value' => isset($productLink->ean) ? $productLink->ean : $product->ean
                            ],
                            [
                                'key' => '_xs_id',
                                'value' => $product->id
                            ]
                        ]
                    ];
                    array_push($data['create'], $productData);
                }
                Log::info(json_encode($data));
                $response = $woocommerce->post('products/batch', $data);
                Log::info(json_encode($response ));
                foreach ($response ->create as $uploadedProduct) {
                    Log::debug(json_encode($uploadedProduct));
                    $productLink = ProductSalesChannel::where('product_id', $uploadedProduct->meta_data[1]->value)->where('sales_channel_id', $salesChannel->id)->first();
                    $productLink->external_id = $uploadedProduct->id;
                    $productLink->save();
                }

            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }

    }

    protected function updateSimpleProducts(Collection $products, SalesChannel $salesChannel, Client $woocommerce)
    {
        Log::info('updating simple products');
        Log::info($products);
        $productBatchs = $products->chunk(100);
        try{
            foreach ($productBatchs as $productBatch) {
                $data = ['update' => []];
                foreach($productBatch as $product){//prepare data
                    $productLink = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first();
                    //prepare properties
                    $properties = $this->prepareProductPropertyData($product, $salesChannel); //null reference
                    //prepare categories
                    $categories = $this->prepareProductCategoryData($product, $productLink);

                    $productData = [
                        'id' => $productLink->external_id,
                        'name' => isset($productLink->title) ? $productSalesChannel->title : $product->title,
                        'short_description' => isset($productLink->short_description) ? $productSalesChannel->short_description : $product->short_description,
                        'sku' => isset($productLink->sku) ? $productSalesChannel->sku : $product->sku,
                        'type' => 'simple',
                        'regular_price' => isset($productLink->price) ? $productSalesChannel->price : $product->price,
                        'sale_price' => isset($productLink->discount) ? ($productSalesChannel->discount) : ($product->discount ? $product->discount : ''), //if discount is null set value empty string. WIP
                        'description' => isset($productLink->long_description) ? $productSalesChannel->long_description : $product->long_description,
                        'categories' => $categories,
                        'attributes' => $properties,
                        'manage_stock' => $product->communicate_stock,
                        'stock_quantity' => $product->stock,
                        'backorders' => $product->backorders ? 'yes' : 'no',
                        'meta_data' => [
                            [
                                'key' => '_ean_code',
                                'value' => isset($productLink->ean) ? $productLink->ean : $product->ean
                            ],
                            [
                                'key' => '_xs_id',
                                'value' => $product->id
                            ]
                        ]
                    ];
                    array_push($data['update'], $productData);
                }

                $response = $woocommerce->post('products/batch', $data);

            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }

    }

    protected function uploadVariableProducts(Collection $products, SalesChannel $salesChannel, Client $woocommerce){
        try{
            foreach($products as $product){//prepare data
                $productLink = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first();
                //prepare properties
                $properties = $this->prepareMainProductPropertyData($product, $salesChannel);
                Log::info("prepared properties");
                //prepare categories
                $categories = $this->prepareProductCategoryData($product, $productLink);
                $data = [
                    'name' => isset($productLink->title) ? $productSalesChannel->title : $product->title,
                    'short_description' => isset($productLink->short_description) ? $productSalesChannel->short_description : $product->short_description,
                    'sku' => isset($productLink->sku) ? $productSalesChannel->sku : $product->sku,
                    'type' => 'variable',
                    'regular_price' => isset($productLink->price) ? $productSalesChannel->price : $product->price,
                    'sale_price' => isset($productLink->discount) ? ($productSalesChannel->discount) : ($product->discount ? $product->discount : ''),
                    'description' => isset($productLink->long_description) ? $productSalesChannel->long_description : $product->long_description,
                    'categories' => $categories,
                    'attributes' => $properties,
                    'manage_stock' => $product->communicate_stock,
                    'backorders' => $product->backorders ? 'yes' : 'no',
                    'meta_data' => [
                        [
                            'key' => '_xs_id',
                            'value' => $product->id
                        ]
                    ]
                ];

                Log::info('uploading product');
                Log::info(json_encode($data));
                $response = $woocommerce->post('products', $data);
                Log::info('product response');
                Log::info(json_encode($response));

                $productLink->external_id = $response->id;
                $productLink->save();
                $this->uploadVariationsData($product, $salesChannel, $woocommerce);
            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }

    protected function uploadVariationsData(Product $product, SalesChannel $salesChannel, Client $woocommerce){
        try{
            Log::info('preparing variations');
            $variations = ['create' => []];
            $productBatchs = $product->childProducts->chunk(100); //foreach child product create a variation
            foreach($productBatchs as $productBatch){
                foreach($productBatch as $variation){
                    Log::info('preparing variation properties');
                    $properties = $this->propareVariationPropertyData($variation, $salesChannel);
                    Log::info('creating variation');
                    $variation = [
                        'regular_price' => $variation->price,
                        'sale_price' => $variation->discount,
                        'sku' => $variation->sku,
                        'description' => $variation->long_description,
                        'manage_stock' => $variation->communicate_stock,
                        'stock_quantity' => $variation->stock,
                        'attributes' => $properties,
                        'meta_data' => [
                            [
                                'key' => '_ean_code',
                                'value' => $variation->ean
                            ],
                            [
                                'key' => '_xs_id',
                                'value' => $variation->id
                            ]
                        ]
                    ];
                    array_push($variations['create'], $variation);
                }

                $externalId = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first()->external_id;
                Log::info('posting variations');
                Log::info(json_encode($variations));
                $response = $woocommerce->post('products/'. $externalId . '/variations/batch', $variations);
                Log::info('varaitions response');
                Log::info(json_encode($response));
                Log::info('setteng varation external ids');
                foreach($response->create as $newVariation){
                    Log::info(json_encode($newVariation));
                    $variation = Product::find($newVariation->meta_data[1]->value); // Retrieve the variation product using the external ID
                    $variationLink = ProductSalesChannel::where('product_id', $variation->id)->where('sales_channel_id', $salesChannel->id)->first();
                    if($variationLink == null){
                        $variationLink = ProductSalesChannel::create([
                            'product_id' => $variation->id,
                            'sales_channel_id' => $salesChannel->id,
                            'external_id' => $newVariation->id
                        ]);
                    }else{
                        $variationLink->external_id = $newVariation->id;
                        $variationLink->save();
                    }

                    $variationLink->save();
                }
            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }


    protected function propareVariationPropertyData(Product $variation, SalesChannel $salesChannel){
        $data = [];
        foreach($variation->properties as $property){
            $propertyLink = ProductProperty::where('product_id', $variation->id)->where('property_id', $property->id)->first();
            $propertyValue = json_decode($propertyLink->property_value);
            $propertySalesChannel = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
            Log::info('property saleschannel');
            Log::info(json_encode($propertySalesChannel));
            Log::info(json_encode($property));
            $propertyData = [
                'id' => $propertySalesChannel->external_id,
                'option' => $propertyValue->value
            ];
            array_push($data, $propertyData);
        }
        return $data;
    }

    protected function prepareMainProductPropertyData(Product $product, SalesChannel $salesChannel){
        $productProperty = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first();

        try{
            $data = [];
            foreach ($product->properties as $property){// first collect the properties of the product
                $propData = [
                    'id'   => null,
                    'options' => null,
                    'variation' => false
                ];

                //add the external id as the prop id
                $proplink = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
                $propData['id'] = $proplink->external_id;

                //get the value of the property
                $productProperty = ProductProperty::where('product_id', $product->id)->where('property_id', $property->id)->first();
                $propertyValue = json_decode($productProperty->property_value);
                $propData['options'] = is_array($propertyValue->value) ? implode(',', array_map('strval', $propertyValue->value)) : (string)$propertyValue->value;

                //add property to data
                array_push($data, $propData);
            }

            //collect all the properties of the child products
            $childProperties = Property::whereIn('id', ProductProperty::whereIn('product_id', $product->childProducts->pluck('id'))->pluck('property_id'))->get();
            Log::info('child properties');
            Log::info(json_encode($childProperties));
            //through all child product properties


            foreach($childProperties as $property){
                $propData = [
                    'id'   => null,
                    'options' => [],
                    'variation' => true
                ];
                $externalId = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first()->external_id;
                $propData['id'] = $externalId;
                $options= [];
                $propertyLinks = ProductProperty::where('property_id', $property->id)->whereIn('product_id', $product->childProducts->pluck('id'))->get();
                foreach ($propertyLinks as $propertyLink) {
                    $propertyValue = json_decode($propertyLink->property_value);
                    $propertyValue= is_array($propertyValue->value) ? implode(',', array_map('strval', $propertyValue->value)) : (string)$propertyValue->value;
                    array_push($options, $propertyValue);
                }
                $propData['options'] = $options;
                array_push($data, $propData);
            }
            Log::info('propdata');
            Log::info(json_encode($data));
            return $data;
        }catch(Exception $ex){
            Log::error($ex->getMessage());
            return [];
        }
    }

    protected function prepareProductPropertyData(Product $product, SalesChannel $salesChannel)
    {
        $productLink = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->first();
        $productPropertiesLink = ProductSalesChannelProperty::where('product_sales_channel_id', $productLink->id)->get();
        //check if the saleschannelproductproperties are presend.
        try{
            if($productPropertiesLink->count() > 0){
                //prepare properties of the link
                $data = [];
                foreach ($productPropertiesLink->properties as $property) {
                    $properSaleschannel = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
                    $propertyProductLink = ProductSalesChannelProperty::where('product_id', $product->id)->where('property_id', $property->id)->first();
                    //prepare the value of the property

                    $propertyValue = json_decode($propertyProductLink->property_value);
                    array_push($data, [
                        'id' => $properSaleschannel->external_id,
                        'options' => $propertyValue->value
                    ]);
                }
                return $data;
            }else{
                //prepare default properties
                $data = [];
                foreach ($product->properties as $property) {

                    $properSaleschannel = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
                    $propertyProductLink = ProductProperty::where('product_id', $product->id)->where('property_id', $property->id)->first();
                    //prepare the value of the property
                    $propertyValue = json_decode($propertyProductLink->property_value);
                    array_push($data, [
                        'id' => $properSaleschannel->external_id,
                        'options' => $propertyValue->value
                    ]);
                }
                Log::info(json_encode($data));
                Log::info('property data');
                return $data;
            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
            return [];
        }

    }

    protected function prepareProductCategoryData(Product $product, ProductSalesChannel $productSalesChannel)
    {
        try{
            $categoryIds = CategoryProductSalesChannel::where('product_sales_channel_id', $productSalesChannel->id)->pluck('category_id')->toArray();
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
                $categoryIds = CategorySalesChannel::whereIn('category_id', $categoryIds)->pluck('external_id')->toArray();
                $categories = [];
                foreach ($categoryIds as $category) {
                    array_push($categories, ['id' => $category]);
                }
                return $categories;
            }
        }catch(Exception $ex){
            Log::error($ex->getMessage());
            return [];
        }

    }

    #endregion

    #region Properties

    //Upload or update properties to a saleschannel
    protected function uploadPropertiesToSaleschannel(Collection $properties, SalesChannel $salesChannel, Client $woocommerce){
        $propertiesToUpload = [];
        $propertiesToUpdate = [];

        foreach ($properties as $property) {
            if(PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->exists()){
                array_push($propertiesToUpdate, $property);
            }else{
                PropertySalesChannel::create([
                    'property_id' => $property->id,
                    'sales_channel_id' => $salesChannel->id
                ]);
                array_push($propertiesToUpload, $property);
            }
        }
        if(count($propertiesToUpload) > 0){
            $this->uploadProperties($propertiesToUpload, $salesChannel, $woocommerce);
        }
        if(count($propertiesToUpdate) > 0){
            $this->updateProperties($propertiesToUpdate, $salesChannel, $woocommerce);
        }
    }

    //actual upload of properties to a saleschannel and set the external id of the properties
    protected function uploadProperties(array $properties,SalesChannel $salesChannel, Client $woocommerce){
        $properties = collect($properties)->chunk(100);
        foreach ($properties as $propertyBatch) {
            $data = ['create' => []];
            foreach ($propertyBatch as $property) {
                $property = [
                    'name' => $property->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $property->name //add a prefix to slug to know it is a ximostock managed property
            ];
                array_push($data['create'], $property);
            }
            $response = $woocommerce->post('products/attributes/batch', $data);
            Log::info(json_encode($response ));
            //Add external id to the properties

            try {
                foreach ($response ->create as $uploadedProperty) {
                    $current_workspace = (int) session('active_workspace_id');
                    $property = Property::where('name', $uploadedProperty->name)->where('work_space_id', $current_workspace)->first();
                    $property = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
                    $property->external_id = $uploadedProperty->id;
                    $property->save();
                }
            } catch (Exception $ex) {
                // Handle the exception here
                Log::error($ex->getMessage());
            }
        }
    }

    //update uploaded products
    protected function updateProperties(array $properties,SalesChannel $salesChannel, Client $woocommerce){
        $properties = collect($properties)->chunk(100);
        foreach ($properties as $propertyBatch) {
            $data = ['update' => []];
            foreach ($propertyBatch as $property) {
                $propertyConnetion = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->first();
                $property = [
                    'id' => $propertyConnetion->external_id,
                    'name' => $property->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $property->name //add a prefix to slug to know it is a ximostock managed property
                ];
                array_push($data['update'], $property);
            }
            $response = $woocommerce->post('products/attributes/batch', $data);
            Log::info(json_encode($response ));
        }
    }

    #endregion

    #region Categories
    //upload all given categories based on id.
    protected function uploadCategoriesToSalesChannel(Collection $categories, SalesChannel $salesChannel, Client $woocommerce)
    {
        //collect data
        $categoryIdsToUpload = $categories->pluck('id')->toArray();
        $categoryIdsToUpload = array_diff($categoryIdsToUpload, CategorySalesChannel::where('sales_channel_id', $salesChannel->id)->pluck('category_id')->toArray());
        $categoriesToUpload = Category::whereIn('id', $categoryIdsToUpload)->get();

        $categoriesToUpdate = $categories->diff($categoriesToUpload);

        //upload all categories that where not uploaded yet
        foreach ($categoriesToUpload as $category) {
            $this->uploadCategoryRecursive($category, $salesChannel, $woocommerce);
        }
        //update all categories that where already uploaded
        $this->updateCategories($categoriesToUpdate, $salesChannel, $woocommerce);
    }

    protected function uploadCategoryRecursive(Category $category, SalesChannel $salesChannel, Client $woocommerce){
        //check if category has a parent or not
        if($category->parent_category_id != null){
            //check of parent is uploaded
            $parentLink = CategorySalesChannel::where('category_id', $category->parent_category_id)->where('sales_channel_id', $salesChannel->id)->first();
            if($parentLink == null){
                //upload parent
                $parent = Category::find($category->parent_category_id);
                $this->uploadCategoryRecursive($parent, $salesChannel, $woocommerce);
            }
        }

        //upload category
        $this->uploadCategory($category, $salesChannel, $woocommerce);
    }

    protected function uploadCategory(Category $category, SalesChannel $salesChannel, Client $woocommerce){
        //upload category
        Log::info($category);
        $data = [
            'name' => $category->name,
            'slug' =>  env('XS_PREFIX', 'xs_') . $category->name,
            'parent' => $category->parent_category_id ? CategorySalesChannel::where('category_id', $category->parent_category_id)->where('sales_channel_id', $salesChannel->id)->first()->external_id : 0,
        ];
        //add parent if it exists
        if($category->parent_category_id != null){
            array_push($data, []);
        }

        Log::info(json_encode($data));
        try{
        $response = $woocommerce->post('products/categories', $data);
        Log::info(json_encode($response ));

            CategorySalesChannel::create([
                'category_id' => $category->id,
                'sales_channel_id' => $salesChannel->id,
                'external_id' => $response ->id
            ]);
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }

    //update names and slugs of categories
    protected function updateCategories(Collection $categories, SalesChannel $salesChannel, Client $woocommerce){
        Log::info('updating categories');
        $categories = $categories->chunk(100);
        foreach ($categories as $categoryBatch) {
            $data = ['update' => []];
            foreach ($categoryBatch as $category) {
                $categoryConnetion = CategorySalesChannel::where('category_id', $category->id)->where('sales_channel_id', $salesChannel->id)->first();
                $cat = [
                    'id' => $categoryConnetion->external_id,
                    'name' => $category->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $category->name //add a prefix to slug to know it is a ximostock managed category
                ];
                array_push($data['update'], $cat);
            }
            $response = $woocommerce->post('products/categories/batch', $data);
            Log::info(json_encode($response ));
        }
    }
    #endregion
}
