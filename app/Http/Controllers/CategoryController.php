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
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('work_space_id', session('active_workspace_id'))->where('parent_category_id', null)->with('child_categories_recursive')->get();
        Log::info($categories);

        return view('category.index', ['categoriesTree' => $categories]);
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

        if ($request->addCategory == null) {
            return response()->json(['message' => 'Category name is required'], 422);
        }

        $newCategory = Category::create([
            'name' => $request->addCategory,
            'work_space_id' => session('active_workspace_id'),
            'parent_category_id' => $request->category['id']
        ]);

        $categories = Category::with('child_categories_recursive')->find($request->category['id']);

        return response()->json(['message' => 'Category has been added succesfully' , 'category' => $categories], 200);
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

    public function deleteCategories(Request $request)
    {
        $categoryArray = $request->input('ids', []); // Get the IDs from the request
        
        // Process each category ID or nested array
        foreach ($categoryArray as $categoryData) {
            if (is_array($categoryData)) {
                // If it's an array, it means it has children
                $category = Category::find($categoryData['id']);
                if ($category) {
                    if (isset($categoryData['children']) && !empty($categoryData['children'])) {
                        Log::debug("delete children");
                        Log::debug($categoryData['id']);
                        Log::debug($categoryData['children']);
                        $this->deleteChildren($categoryData['children']);
                    }
                    Log::debug("delete category with ID: " . $category->id);
                    $category->delete();
                }
            } else {
                // Otherwise, it's a simple ID
                $category = Category::find($categoryData);
                if ($category) {
                    Log::debug("delete category with ID: " . $category->id);
                    $category->delete();
                }
            }
        }

        return response()->json(['message' => 'Categories deleted successfully'], 200);
    }

    public function deleteChildren(array $children)
    {
        foreach ($children as $childData) {
            $child = Category::find($childData['id']);
            if ($child) {
                if (isset($childData['children']) && !empty($childData['children'])) {
                    $this->deleteChildren($childData['children']);
                }
                Log::debug("delete child with ID: " . $child->id);
                $child->delete();
            }
        }
    }

    public function updateCategories(Request $request)
    {
        $categories = $request->input('categories');
        log::debug($categories);

        // Validate the request
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|integer|exists:categories,id',
            'categories.*.name' => 'required|string|max:255',
        ]);

        foreach ($categories as $categoryData) {
            $category = Category::find($categoryData['id']);
            if ($category) {
                $category->update($categoryData);
                if (isset($categoryData['children'])) {
                    $this->saveChildren($categoryData['children'], $category->id);
                }
            }
        }

        return response()->json(['message' => 'Categories updated successfully'], 200);
    }

    private function saveChildren(Array $children, $parentId = null)
    {
        foreach ($children as $child) {
            $category = Category::find($child['id']);
            if ($category) {
                $category->update($child);
                if (isset($child['children'])) {
                    Log::debug($category->id);
                    $this->saveChildren($child['children'], $category->id);
                }
            }
        }
    }
}
