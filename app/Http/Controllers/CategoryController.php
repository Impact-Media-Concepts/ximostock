<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return view('category.index',[
            'categories' => Category::with(['child_categories', 'parent_category'])->get()
        ]);
    }

    public function show(Category $category){
        return view('category.show',[
            'category' => $category
        ]);
    }
}
