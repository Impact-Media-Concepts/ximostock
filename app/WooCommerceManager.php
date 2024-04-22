<?php

namespace App;

use App\Models\Category;
use App\Models\CategorySalesChannel;
use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\PropertySalesChannel;
use App\Models\SalesChannel;
use Automattic\WooCommerce\Client;
use Exception;

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

    public function uploadOrUpdateProduct(Product $product)
    {
        foreach ($product->salesChannels as $salesChannel) {
            $this->uploadOrUpdateProductSalesChannel($product, $salesChannel);
        }
    }

    public function uploadOrUpdateProductSalesChannel(Product $product, SalesChannel $salesChannel)
    {
        if (ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->whereNotNull('external_id')->exists()) {
            if ($this->productOnSalesChannel($product, $salesChannel)) {
                $this->updateProductToSalesChannel($product, $salesChannel);
            } else {
                $this->uploadProductToSalesChannel($product, $salesChannel);
            }
        } else {
            $this->uploadProductToSalesChannel($product, $salesChannel);
        }
    }

    public function deleteProductFromSalesChannel(Product $product, SalesChannel $salesChannel)
    {
        if ($this->productOnSalesChannel($product, $salesChannel)) {
            $woocommerce = $this->createSalesChannelsClient($salesChannel);
            $external_id = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first()->external_id;
            $woocommerce->delete('products/' . $external_id, ['force' => true]);
        }
    }

    public function uploadProductToSalesChannel(Product $product, SalesChannel $salesChannel)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $this->uploadProductCategoriesToSalesChannel($product, $salesChannel);
        $this->uploadOrUpdateProperties($product, $salesChannel);

        foreach ($product->properties as $property) {
            $this->UploadProperty($property, $salesChannel);
        }

        $categories = [];
        foreach ($product->categories as $category) {
            $externalId = CategorySalesChannel::where('category_id', $category->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
            $externalId = $externalId->external_id;
            array_push($categories, ['id' => $externalId]);
        }
        $data = [
            'name' => $product->title,
            'type' => 'simple',
            'sku' => $product->sku,
            'regular_price' => $product->price,
            'sale_price' => $product->discount,
            'description' => $product->long_description,
            'short_description' => $product->short_description,
            'backorders' => $product->backorders ? 'yes' : 'no',
            'categories' => $categories,
            // 'images' => [
            //     [
            //         'src' => 'https://nerf-pijltjes.nl/wp-content/uploads/2022/03/NERF-Elite-2.0-Eaglepoint-RD-8-1.jpg'
            //     ]
            // ]
        ];
        $result = $woocommerce->post('products', $data);
        $productSalesChannel = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
        $productSalesChannel->external_id = $result->id;
        $productSalesChannel->save();
    }

    public function updateProductToSalesChannel(Product $product, SalesChannel $salesChannel)
    {
        if ($salesChannel != null) {
            $woocommerce = $this->createSalesChannelsClient($salesChannel);
            $this->uploadProductCategoriesToSalesChannel($product, $salesChannel);
            $categories = [];
            foreach ($product->categories as $category) {
                $externalId = CategorySalesChannel::where('category_id', $category->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
                $externalId = $externalId->external_id;
                array_push($categories, ['id' => $externalId]);
            }
            $data = [
                'name' => $product->title,
                'type' => 'simple',
                'sku' => $product->sku,
                'regular_price' => $product->price,
                'sale_price' => $product->discount,
                'description' => $product->long_description,
                'short_description' => $product->short_description,
                'backorders' => $product->backorders ? 'yes' : 'no',
                'categories' => $categories
            ];

            $productSalesChannel = ProductSalesChannel::where('product_id', $product->id)->where('sales_channel_id', $salesChannel->id)->get()->first();
            $result = $woocommerce->post('products/' . $productSalesChannel->external_id, $data);
        }
    }

    protected function uploadProductCategoriesToSalesChannel(Product $product, SalesChannel $salesChannel)
    {
        $categories = $product->categories->pluck('id')->toArray();
        $categories = array_diff($categories, $salesChannel->categories->pluck('id')->toArray());
        $categories = Category::whereIn('id', $categories)->get();
        $woocommerce = $this->createSalesChannelsClient($salesChannel);

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

    protected function uploadOrUpdateProperties(Product $product, SalesChannel $salesChannel)
    {
        foreach ($product->properties as $property) {
            $this->uploadProperty($property, $salesChannel);
        }
    }

    protected function uploadProperty(Property $property, SalesChannel $salesChannel, bool $update)
    {
        $woocommerce = $this->createSalesChannelsClient($salesChannel);
        $propertySalesChannel = PropertySalesChannel::where('property_id', $property->id)->where('sales_channel_id', $salesChannel->id)->whereNotNull('external_id')->get()->first();
        if ($propertySalesChannel == null) {
            $data = [
                'name' => $property->name,
                'type' => 'select',
                'order_by' => 'menu_order'
            ];
            if($update){
                
                $result = $woocommerce->post('products/attributes', $data);
            }else{
                $result = $woocommerce->post('products/attributes', $data);
                PropertySalesChannel::create([
                    'property_id' => $property->id,
                    'sales_channel_id' => $salesChannel->id,
                    'external_id' => $result->id
                ]);
            }
        }
    }

    //check if theree are any differences between the sales channel and the ximostock database and if so. correct these differences.
    protected function overrideWoocommerce(){

    }
}
