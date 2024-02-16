<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return view('product.index', [
            'products' => Product::all()
        ]);
    }

    public function create(){
        return view('product.create',[]);
    }

    public function show(Product $product){
        return view('product.show',[
            'product' => $product,
            'categories'=> Category::all()
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
