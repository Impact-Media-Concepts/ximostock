<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\InventoryLocation;
use App\Models\SalesChannel;
use App\Models\Property;
use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\Photo;
use App\Models\PhotoProduct;
use App\Models\ProductProperty;
use OCILob;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;

class ProductVariationController extends Controller
{

    public function create()
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }
        return view('product.createVariant', [
            'categories' => Category::with(['parent_category', 'child_categories'])->get(),
            'properties' => $properties,
            'locations' => InventoryLocation::with(['location_zones'])->get(),
            'salesChannels' => SalesChannel::all()
        ]);
    }

    public function store()
    {
        $request = request();
        //dd($request);
        // Validate the incoming request data
        $mainProductAttributes = $this->validateMainProductAttributes($request);
        $categoryAttributes = $this->validateCategoryAttributes($request);
        $saleschannelAttributes = $this->validateSalesChannelAttributes($request);
        $this->validatePhotoAttributes($request);
        $this->validatePropertyAttributes($request);
        $variants = $this->validateVariantAttributes($request);

        
        //create main product
        $mainProduct = Product::create($mainProductAttributes);
        $this->linkCategoriesToProduct($mainProduct, $categoryAttributes);
        $this->uploadAndLinkPhotosToProduct($mainProduct, $request);
        $this->linkPropertiesToProduct($mainProduct, $request);
        if ($saleschannelAttributes['salesChannels'] != null) {
            $this->linkSalesChannelsToProduct($mainProduct, $saleschannelAttributes);
        }
        //ceate product variants
        $this->createVariantProducts($mainProduct, $variants['variants']);

        return redirect('/products');
    }


    protected function validateSalesChannelAttributes($request):array
    {
        $attributes = $request->validate([
            'salesChannels' => ['array'],
            'salesChannels.*' => ['numeric']
        ]);
        if ($request['salesChannels'] == null) {
            $attributes['salesChannels'] = [];
        }
        return $attributes;
    }

    protected function validateMainProductAttributes($request):array{
        return $request->validate([
            'title' => ['required', 'string'],
            'short_description' => ['required', 'string'],
            'long_description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'backorders'=>['boolean'],
            'communicate_stock'=>['boolean']
        ]);
    }

    protected function validateCategoryAttributes($request):array
    {
        return $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'numeric'],
            'primaryCategory' => ['required', 'numeric']
        ]);
    }

    protected function validatePhotoAttributes($request)
    {
        return $request->validate([
            'primaryPhoto' => ['required', 'image'],
            'photos' => ['required', 'array'],
            'photos.*' => ['required', 'image']
        ]);
    }
    
    protected function validatePropertyAttributes($request)
    {
        return $request->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'string']
        ]);
    }

    protected function validateVariantAttributes($request){
        return $request->validate([
            'variants' => ['required','array'],
            'variants.*' => ['required','array'],
            'variants.*.property_id' => ['required', 'array'],
            'variants.*.property_id.*' => ['required', 'numeric'],
            'variants.*.property_value' => ['required', 'array'],
            'variants.*.property_value.*' => ['required','string'],
            'variants.*.sku' => ['required', 'string'],
            'variants.*.ean' => ['digits:13', 'nullable'],
            'variants.*.price' => ['nullable'],
            'variants.*.location_zones' => ['array'],
            'variants.*.location_zones.*' => ['numeric']
        ]);
    }

    protected function linkSalesChannelsToProduct($product, $attributes)
    {
        foreach ($attributes['salesChannels'] as $salesChannel) {
            ProductSalesChannel::create([
                'product_id' => $product->id,
                'sales_channel_id' => $salesChannel
            ]);
        }
    }

    protected function linkCategoriesToProduct($product, $attributes)
    {
        //link primary
        CategoryProduct::create([
            'category_id' => $attributes['primaryCategory'],
            'product_id' => $product->id,
            'primary' => true
        ]);
        //link all other categories
        foreach ($attributes['categories'] as $categoryId) {
            CategoryProduct::create([
                'category_id' => $categoryId,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }
    }

    protected function linkPropertiesToProduct($product, $request)
    {
        foreach ($request->input('properties') as $propertyId => $propertyValue) {
            ProductProperty::create([
                'product_id' => $product->id,
                'property_id' => $propertyId,
                'property_value' => json_encode(['value' => $propertyValue])
            ]);
        }
    }

    protected function uploadAndLinkPhotosToProduct($product, $request)
    {
        $path = $request->file('primaryPhoto')->store('public/photos');
        $primaryPhoto = Photo::create([
            'url' => str_replace('public', 'http://localhost:8000/storage', $path)
        ]);

        PhotoProduct::create([
            'photo_id' => $primaryPhoto->id,
            'product_id' => $product->id,
            'primary' => true
        ]);

        foreach ($request->file('photos') as $photoFile) {
            $photoPath = $photoFile->store('public/photos');
            $photo = Photo::create([
                'url' => str_replace('public', 'http://localhost:8000/storage', $photoPath)
            ]);

            PhotoProduct::create([
                'photo_id' => $photo->id,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }
    }

    protected function createVariantProducts($product, $variants){
        foreach($variants as $variant){
            //create variant product
            $attributes = [
            'parent_product_id' => $product->id,
            'sku' => $variant['sku'],
            'ean' => $variant['ean']
            ];
            if($variant['price'] != null && $variant['price']  != 0.00){
                array_push($attributes, ['price' => $variant['price']] );
            }
            $vProduct = Product::create(
                $attributes
            );

            //add variant properties
            for($x = 1; $x <= sizeof($variant['property_id']); $x++){
                ProductProperty::create([
                    'product_id' => $vProduct->id,
                    'property_id' => $variant['property_id'][$x],
                    'property_value' => json_encode(['value' => $variant['property_value'][$x]]) 
                ]);
            }

            //add stock
            foreach($variant['location_zones'] as $zone => $stock){
                Inventory::create([
                    'product_id' =>  $vProduct->id,
                    'location_zone_id'=> $zone,
                    'stock' => $stock
                ]);
            }
        }
    }
}