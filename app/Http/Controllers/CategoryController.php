<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index',[
            'categories' => Category::with('child_categories_recursive','products')->whereNull('parent_category_id')->where('work_space_id', Auth::user()->work_space_id)->get()
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
        $parentCategoryId = $request->has('parent_category_id') ? $request['parent_category_id'] : null;

        Gate::authorize('store-category',[ $parentCategoryId]);

        //validate
        $attributes = $request->validate([
            'name' => ['required'],
            'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
        ]);
        $attributes += ['work_space_id'=>Auth::user()->work_space_id];

        Category::create($attributes);

        return redirect('/categories');
    }

    //add the parent category id
    public function update(Category $category){
        Gate::authorize('update-category', [$category, null]); 
        $attributes = request()->validate([
            'name'=>['required', 'string'],
            'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
        ]);

        $category->update([
            'name'=>$attributes['name'],
            'parent_category_id' => $attributes['parent_category_id']
        ]);

        return redirect()->back();
    }
}
