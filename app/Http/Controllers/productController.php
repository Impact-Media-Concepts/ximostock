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
use App\Models\Property;

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
            'locations' => InventoryLocation::with(['location_zones'])->get()
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

    public function store()
    {
        //validate request
        $productAttributes = request()->validate([
            'sku' => ['required', 'max:255'],
            'ean' => ['Digits:13', 'nullable'],
            'title' => ['required', 'max:255'],
            'price' => ['required','numeric'],
            'long_description' => ['max:32000', 'nullable'],
            'short_description' => ['max:32000', 'nullable']
        ]);

        $categoryAttributes = request()->validate([
            'categories' => ['required','array'],
            'categories.*' => ['required','numeric'],
            'primaryCategory' => ['required','numeric']
        ]);

        request()->validate([
            'primaryPhoto' => ['required', 'image'],
            'photos' => ['required', 'array'],
            'photos.*' => ['required', 'image'],

            'properties' => ['required','array'],
            'properties.*' => ['required','string'],

            'location_zones' => ['required','array'],
            'lacation_zones.*' => ['required', 'numeric']
        ]);

        //create product
        $product = Product::create($productAttributes);        

        //link primary category
        CategoryProduct::create([
            'category_id' => $categoryAttributes['primaryCategory'],
            'product_id' => $product->id,
            'primary' => true
        ]);
        //link categories to product
        foreach ($categoryAttributes['categories'] as $categoryId) {
            CategoryProduct::create([
                'category_id' => $categoryId,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }

        //upload photo
        $path = request()->file('primaryPhoto')->store('public/photos');

        //add photos to product
        $primaryPhoto = Photo::create([
            'url' => str_replace('public', 'http://localhost:8000/storage', $path)
        ]);

        //link primary photo
        PhotoProduct::create([
            'photo_id' => $primaryPhoto->id,
            'product_id' => $product->id,
            'primary' => true
        ]);

        //link photos
        foreach (request()->file('photos') as $photoFile) {
            $photoPath = $photoFile->store('public/photos');
            //upload photos
            $photo = Photo::create([
                'url' => str_replace('public', 'http://localhost:8000/storage', $photoPath)
            ]);

            PhotoProduct::create([
                'photo_id' => $photo->id,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }

        //link properties
        foreach (request()->input('properties') as $propertyId => $propertyValue) {
            // Create or update the PropertyProduct entry
            ProductProperty::create([
                'product_id' => $product->id,
                'property_id' => $propertyId,
                'property_value' => json_encode(['value' => $propertyValue])
            ]);
        }

        foreach (request()->input('location_zones') as $location_zone_id => $stock){
            Inventory::create([
                'product_id' => $product->id,
                'location_zone_id' => $location_zone_id,
                'stock' => $stock
            ]);
        }


        return redirect('/products');
    }
}
