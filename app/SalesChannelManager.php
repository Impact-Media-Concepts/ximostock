<?php

namespace App;
use App\Models\Category;
use App\Models\CategorySalesChannel;
use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\PropertySalesChannel;
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
    public function UploadProductsToSaleschannel(Collection $products, SalesChannel $salesChannel)
    {
        //create a connection to the saleschannel
        $woocommmerce = $this->createSalesChannelsClient($salesChannel);

        //upload or update properties of products
        $properties = $products->flatMap(function ($product) {
            return $product->properties;
        });
        $properties = Property::whereIn("id", $properties->pluck("id"))->get();
        $this->uploadPropertiesToSaleschannel($properties, $salesChannel, $woocommmerce);

        //upload or update categories of products
        $categories = $products->flatMap(function ($product) {
            return $product->categories->pluck('id');
        })->unique()->toArray();
        $categories = Category::whereIn("id", $categories)->get();
        $this->uploadCategoriesToSalesChannel($categories, $salesChannel, $woocommmerce);

        //collect from all products the data of what needs to happen.
        $prodcutsToCreate = [];
        $prodcutsToUpdate = [];
        foreach ($products as $product) {
            if($this->productIsLinked($product, $salesChannel)){
                array_push($prodcutsToCreate, $product);
            }else{
                array_push($prodcutsToUpdate, $product);
            }
        }
    }

    //if a product is already linked to a saleschannel return false. (being linked means an entry in the pivot table exits. it is not jet live on the saleschannel)
    //Otherwise create a link and return true
    protected function productIsLinked(Product $product, SalesChannel $salesChannel)
    {
        if($product->salesChannels->contains($salesChannel)){
            return false;
        }else{
            ProductSalesChannel::create([
                'product_id' => $product->id,
                'sales_channel_id' => $salesChannel->id
            ]);
            return true;
        }
    }

    #region Properties

    //Upload or update properties to a saleschannel
    protected function uploadPropertiesToSaleschannel(Collection $properties, SalesChannel $salesChannel, Client $woocommerce){
        Log::info('uploading properties');
        $propertiesToUpload = [];
        $propertiesToUpdate = [];

        foreach ($properties as $property) {
            Log::debug(json_encode(PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->exists()) );
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
        Log::debug(json_encode($propertiesToUpload));
        Log::debug(json_encode($propertiesToUpdate));
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
                $prop = [
                    'name' => $property->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $property->name //add a prefix to slug to know it is a ximostock managed property
            ];
                array_push($data['create'], $prop);
            }
            $responce = $woocommerce->post('products/attributes/batch', $data);
            Log::info(json_encode($responce));
            //Add external id to the properties

            try {
                foreach ($responce->create as $uploadedProperty) {
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
                $prop = [
                    'id' => $propertyConnetion->external_id,
                    'name' => $property->name,
                    'slug' => env('XS_PREFIX', 'xs_') . $property->name //add a prefix to slug to know it is a ximostock managed property
                ];
                array_push($data['update'], $prop);
            }
            $responce = $woocommerce->post('products/attributes/batch', $data);
            Log::info(json_encode($responce));
        }
    }

    #endregion

    #region Categories
    //upload all given categories based on id.
    protected function uploadCategoriesToSalesChannel(Collection $categories, SalesChannel $salesChannel, Client $woocommerce)
    {
        Log::info('uploading categories');
        //collect data
        $categoryIdsToUpload = $categories->pluck('id')->toArray();
        $categoryIdsToUpload = array_diff($categoryIdsToUpload, CategorySalesChannel::where('sales_channel_id', $salesChannel->id)->pluck('category_id')->toArray());
        $categoriesToUpload = Category::whereIn('id', $categoryIdsToUpload)->get();

        $categoriesToUpDate = $categories->diff($categoriesToUpload);

        //upload all categories that where not uploaded yet
        foreach ($categoriesToUpload as $category) {
            $this->uploadCategoryRecursive($category, $salesChannel, $woocommerce);
        }
        //update all categories that where already uploaded
        $this->updateCategories($categoriesToUpDate, $salesChannel, $woocommerce);
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
        $responce = $woocommerce->post('products/categories', $data);
        Log::info(json_encode($responce));

            CategorySalesChannel::create([
                'category_id' => $category->id,
                'sales_channel_id' => $salesChannel->id,
                'external_id' => $responce->id
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
            $responce = $woocommerce->post('products/categories/batch', $data);
            Log::info(json_encode($responce));
        }
    }
    #endregion
}
