<?php

namespace App\Http\Controllers;

use App\Helpers\CategoryHelper;
use App\Models\Category;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use App\WooCommerceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Facades\LogBatch;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('work_space_id', session('active_workspace_id'))->get();

        $categoriesTree = $this->buildCategoryTree($categories);

        return view('category.index', ['categoriesTree' => $categoriesTree]);
    }

    private function buildCategoryTree($categories, $parentId = null)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_category_id == $parentId) {
                $children = $this->buildCategoryTree($categories, $category->id);
                if ($children) {
                    $category->children = $children;
                }
                $branch[] = $category;
            }
        }

        return $branch;
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
            $workspaceId = $attributes['work_space_id'];
        } else {
            //validate
            $attributes = $request->validate([
                'name' => ['required'],
                'parent_category_id' => ['nullable', 'numeric', Rule::exists('categories', 'id')]
            ]);
            $attributes += ['work_space_id' => Auth::user()->work_space_id];
        }

        Category::create($attributes);

        // Build the redirect URL
        $redirectUrl = '/categories';
        if (Auth::user()->role === 'admin') {
            $redirectUrl .= '?workspace=' . $workspaceId;
        }

        return redirect($redirectUrl);
    }

    public function destroy(Category $category)
    {
        LogBatch::startBatch();
        $woocommerce = new WooCommerceManager;
        $woocommerce->deleteCategoriesFromSalesChannels([$category->id]);
        $category->deleteWithChildren();
        LogBatch::endBatch();
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
        $wooCommerce = new WooCommerceManager;
        $wooCommerce->updateCategoryToSaleschannels($category);
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
