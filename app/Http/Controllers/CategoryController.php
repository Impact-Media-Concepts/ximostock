<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
            $categories = Category::where('work_space_id', $request['workspace']);
        } else {
            $workspaces = null;
            $activeWorkspace = null;
            $categories = Category::where('work_space_id', Auth::user()->work_space_id);
        }
        $categories = $categories->with('child_categories_recursive', 'products')->whereNull('parent_category_id')->get();

        return view('category.index', [
            'sidenavActive' => 'categories',
            'categories' => $categories,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function show(Request $request, Category $category)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        } else {
            $workspaces = null;
            $activeWorkspace = null;
        }
        return view('category.show', [
            'sidenavActive' => 'categories',
            'category' => $category,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function create(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        } else {
            $workspaces = null;
            $activeWorkspace = null;
        }

        return view('category.create', [
            'sidenavActive' => 'categories',
            'categories' => Category::all(),
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function store()
    {
        $request = request();
        $parentCategoryId = $request->has('parent_category_id') ? $request['parent_category_id'] : null;

        Gate::authorize('store', [Category::class, $parentCategoryId]);

        if (Auth::user()->role === 'admin') {
            //validate
            $attributes = $request->validate([
                'name' => ['required'],
                'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')],
                'work_space_id' => ['required', 'numeric', Rule::exists('work_spaces', 'id')]
            ]);
        } else {
            //validate
            $attributes = $request->validate([
                'name' => ['required'],
                'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
            ]);
            $attributes += ['work_space_id' => Auth::user()->work_space_id];
        }
        
        Category::create($attributes);

        return redirect('/categories');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect('/categories');
    }

    public function update(Category $category)
    {
        $request = request();
        Gate::authorize('update', [$category, $request['parent_category_id']]);
        $attributes = $request->validate([
            'name' => ['required', 'string'],
            'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
        ]);

        $category->update([
            'name' => $attributes['name'],
            'parent_category_id' => $attributes['parent_category_id']
        ]);
        return redirect()->back();
    }

    public function archive(Request $request)
    {
        $request->validate([
            'workspace' => ['required', new ValidWorkspaceKeys]
        ]);
        $results = [
            'perPage' => $request->input('perPage', 20),
            'search' => $request['search'],
            'sidenavActive' => 'archive',
            'workspaces' => WorkSpace::all(),
            'activeWorkspace' => $request['workspace'],
            'categories' => Category::onlyTrashed()->get()
        ];
        return view('category.archive', $results);
    }

    public function restore(Request $request)
    {
        $attributes = $request->validate([
            'categories' => ['array', 'required'],
            'categories.*' => ['numeric', 'required']
        ]);
        Category::withTrashed()->whereIn('id', $attributes['categories'])->restore();
        return redirect()->back();
    }

    public function forceDelete(Request $request)
    {
        $attributes = $request->validate([
            'categories' => ['array', 'required'],
            'categories.*' => ['numeric', 'required']
        ]);
        Category::withTrashed()->whereIn('id', $attributes['categories'])->forceDelete();
        return redirect()->back();
    }
}
