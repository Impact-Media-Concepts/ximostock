<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\Photo;
use App\Models\PhotoProduct;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\SalesChannel;

class ProductController extends Controller
{
    public function index()
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }

        return view('product.index', [
            'products' => Product::with('photos', 'locationZones')->get(),
            'categories' => Category::with(['parent_category', 'child_categories'])->get(),
            'properties' => $properties
        ]);
    }

    public function create()
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }
        return view('product.create', [
            'categories' => Category::with(['parent_category', 'child_categories'])->get(),
            'properties' => $properties,
            'locations' => InventoryLocation::with(['location_zones'])->get(),
            'salesChannels' => SalesChannel::all()
        ]);
    }

    public function show(Product $product)
    {
        foreach ($product->properties as $prop) {
            $prop->pivot->property_value = json_decode($prop->pivot->property_value);
        }
        return view('product.show', [
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        // Find the product by its ID
        $product = Product::findOrFail($id);

        // Soft delete the product
        $product->delete();

        // Redirect back to the product index page with a success message
        return redirect('/products');
    }

    public function bulkDelete()
    {
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric'],
        ]);

        // Delete selected products
        Product::whereIn('id', $validatedData['product_ids'])->delete();

        return redirect('/products');
    }

    public function store()
    {
        $request = request();
        //valid$ate inp
        $productAttributes = $this->validateProductAttributes($request);
        $categoryAttributes = $this->validateCategoryAttributes($request);
        $saleschannelAttributes = $this->validateSalesChannelAttributes($request);
        $this->validatePhotoAttributes($request);
        $this->validatePropertyAttributes($request);
        $this->validateInventoryAttributes($request);

        //create product and links
        $product = Product::create($productAttributes);
        $this->linkCategoriesToProduct($product, $categoryAttributes);
        $this->uploadAndLinkPhotosToProduct($product, $request);
        $this->linkPropertiesToProduct($product, $request);
        $this->createInventories($product, $request);

        if ($saleschannelAttributes['salesChannels'] != null) {
            $this->linkSalesChannelsToProduct($product, $saleschannelAttributes);
        }
        return redirect('/products');
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

    protected function createInventories($product, $request)
    {
        foreach ($request->input('location_zones') as $location_zone_id => $stock) {
            Inventory::create([
                'product_id' => $product->id,
                'location_zone_id' => $location_zone_id,
                'stock' => $stock
            ]);
        }
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

    protected function validateProductAttributes($request)
    {
        return $request->validate([
            'sku' => ['required', 'max:255'],
            'ean' => ['digits:13', 'nullable'],
            'title' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'long_description' => ['max:32000', 'nullable'],
            'short_description' => ['max:32000', 'nullable'],
            'backorders' => ['required', 'numeric'],
            'communicate_stock' => ['required', 'numeric']
        ]);
    }

    protected function validateCategoryAttributes($request)
    {
        return $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'numeric'],
            'primaryCategory' => ['required', 'numeric']
        ]);
    }

    protected function validateSalesChannelAttributes($request)
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

    protected function validateInventoryAttributes($request)
    {
        return $request->validate([
            'location_zones' => ['required', 'array'],
            'location_zones.*' => ['required', 'numeric']
        ]);
    }
}
