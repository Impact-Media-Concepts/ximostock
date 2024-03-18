<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index',[
            'categories' => Category::with('child_categories_recursive','products')->whereNull('parent_category_id')->get()
        ]);
    }

    public function show(Category $category)
    {
        return view('category.show', [
            'category' => $category
        ]);
    }

    public function create()
    {
        return view('category.create', [
            'categories' => Category::all()
        ]);
    }

    public function store()
    {
        $request = request();

        $attributes = $request->validate([
            'name' => ['required'],
            'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
        ]);

        Category::create($attributes);

        return redirect('/categories');
    }
}
