<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Property;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $properties = Property::all();

        foreach($properties as $prop){
            $prop->values = json_decode($prop->values);
        }      

        return view('product.index', [
            'products' => Product::with('photos', 'locationZones')->get(),
            'categories' => Category::with(['parent_category', 'child_categories'])->get(),
            'properties' => $properties
        ]);
    }

    public function create(){
        return view('product.create',[]);
    }

    public function show(Product $product){
        foreach($product->properties as $prop){
            $prop->pivot->property_value = json_decode($prop->pivot->property_value);
        }
        return view('product.show',[
            'product' => $product
        ]);
    }

    public function store(){

        //validate request
        $attributes = request()->validate([
            'sku' => ['required','max:255'],
            'ean' => ['Digits:13','nullable'],
            'title' => ['required','max:255'],
            'price' => ['required'],
            'long_description' => ['max:32000', 'nullable'],
            'short_description' => ['max:32000', 'nullable']
        ]);

        //create product
        Product::create($attributes);

        return redirect('/products');
    }
}
