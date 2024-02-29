<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['child_categories', 'products', 'parent_category'])
            ->whereNull('parent_category_id')
            ->get();

        // Eager load child categories recursively
        $categories->load('child_categories.child_categories');

        return view('category.index', compact('categories'));
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
