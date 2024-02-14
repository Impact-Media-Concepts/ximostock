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

    public function show(Product $product){
        return view('product.show',[
            'product' => $product,
            'categories'=> Category::all()
        ]);
    }

    public function store(Product $product){
        //validation
        request()->validate([
            'artical_number' => 'required',
            'title' => 'required',
            'price' => 'required'
        ]);

        //create product
        $product->create([
            'artical_number' => request('artical_number'),
            'ean' => request('ean'),
            'title' => request('title'),
            'short_description' => request('short_description'),
            'long_description' => request('long_description'),
            'price' => request('price')
        ]);
    }
}
