<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport; 
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CategoryProductSalesChannel;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\ProductSalesChannelProperty;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\WorkSpace;
use App\Rules\ValidLocationZoneKeys;
use App\Rules\ValidProductIds;
use App\Rules\ValidProductKeys;
use App\Rules\ValidPropertyKeys;
use App\Rules\ValidPropertyOptions;
use App\Rules\ValidSalesChannelKeys;
use App\Rules\ValidWorkspaceKeys;
use App\Rules\VallidCategoryKeys;
use App\WooCommerceManager;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProductController extends BaseProductController
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $current_workspace = (int) session('active_workspace_id');
        $products = Product::where('work_space_id', $current_workspace)
                    ->with(['categories', 'photos' => function ($query) {
                        $query->where('primary', 1);
                    }])
                    ->orderBy('created_at', 'desc') // Order by creation date
                    ->paginate(11); // Use paginate instead of limit

        // Eager loading categories for products
        $products->load('categories');

        // Collecting related categories
        $relatedCategories = $products->pluck('categories')->flatten()->unique('id');

        // Loading hierarchical categories
        $hierarchicalCategories = $relatedCategories->map(function($category) {
            return Category::with('child_categories_recursive')->find($category->id);
        });

        $hierarchicalCategories = $hierarchicalCategories->unique('id')->toArray();

        //loading saleschannels for the user
        $saleschannels = SalesChannel::where('work_space_id', $current_workspace)->orderBy('name', 'desc')->get();

        $data = [
            'products' => $products,
            'categories' => $hierarchicalCategories,
            'saleschannels' => $saleschannels
        ];

        return view('product.index', $data);
    }

    public function create(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
            $salesChannels = SalesChannel::where('work_space_id', $request['workspace'])->get();
            $location_zones = InventoryLocation::with(['location_zones'])->where('work_space_id', $request['workspace'])->get();
            $properties = Property::where('work_space_id', $request['workspace'])->get();
            $categories = Category::where('work_space_id', $request['workspace']);
        } else {
            $workspaces = null;
            $activeWorkspace = null;
            $salesChannels = SalesChannel::where('work_space_id', Auth::user()->work_space_id)->get();
            $location_zones = InventoryLocation::with(['location_zones'])->where('work_space_id', Auth::user()->work_space_id)->get();
            $properties = Property::where('work_space_id', Auth::user()->work_space_id)->get();
            $categories = Category::where('work_space_id', Auth::user()->work_space_id);
        }
        $categories = $categories
            ->with('child_categories_recursive')
            ->whereNull('parent_category_id')
            ->get();

        return view('product.create', [
            'categories' => $categories,
            'properties' => $properties,
            'locations' => $location_zones,
            'sidenavActive' => 'products',
            'salesChannels' => $salesChannels,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function addVariation()
    {
        return view('product.variation');
    }

    public function show(Request $request, Product $product)
    {
        // Eager load all necessary relationships
        $product->load([
            'childProducts',
            'parentProduct',
            'categories',
            'photos',
            'properties',
            'locationZones',
            'salesChannels',
            'productSalesChannels',
            'sales',
        ]);

        // Get the active workspace id from the session
        $activeWorkspaceId = session('active_workspace_id');

        // Get all categories related to the active workspace
        $workspaceCategories = \App\Models\Category::where('work_space_id', $activeWorkspaceId)->get();

        // Get the categories that are already related to the product
        $productCategoryIds = $product->categories->pluck('id')->toArray();

        // Filter out the categories that are already related to the product
        $unrelatedCategories = $workspaceCategories->whereNotIn('id', $productCategoryIds);

        Log::info( "product-propties:");
        // Log::info( $product->properties[0]);

        return view('product.show', [
            'product' => $product,
            'unrelatedCategories' => $unrelatedCategories,
        ]);
    }

    public function update(Request $request, $id)
    {
        
        // Validate the request data
        $request->validate([
            'type' => ['required', Rule::in(['simple', 'variable'])],
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'sku' => 'nullable|string|max:255',
            'ean' => 'nullable|numeric',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
            'properties' => 'array',
            'properties.*.pivot.property_value' => 'string', // Adjust validation as necessary
        ]);

        foreach ($request->child_products as $child_product) {
            // Log::info("Child Product:");
            // Log::info($child_product);
            if($child_product['id'] === null) {
                Log::info("Creating new child product");
                $newProduct = Product::create([
                    'type' => 'simple',
                    'title' => '',
                    'price' => $child_product['price'],
                    'discount' => $child_product['discount'],
                    'sku' => $child_product['sku'],
                    'ean' => $child_product['ean'],
                    'short_description' => $child_product['short_description'],
                    'long_description' => $child_product['long_description'],
                    'stock_quantity' => $child_product['stock_quantity'],
                    'backorders' => $child_product['backorders'],
                    'parent_product_id' => $id,
                    'work_space_id' => $child_product['work_space_id'],
                ]);
            }
        }

        // Find the product by ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update the product with the request data
        $product->update($request->only([
            'type', 'title', 'price', 'discount', 'sku', 'ean', 'short_description', 'long_description', 'stock_quantity', 'backorders'
        ]));


        // Sync the categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        Log::info("product after sync: " . $product);

        // Update properties
        if ($request->has('properties')) {
            $requestPropertyIds = [];

            foreach ($request->properties as $propertyData) {
                Log::info("propertyData: ", $propertyData);
                if (isset($propertyData['pivot']['property_value'])) {
                    if (isset($propertyData['id'])) {
                        // Update the existing property
                        Log::info("Updating property with data: ", $propertyData['pivot']);
                        $product->properties()->updateExistingPivot(
                            $propertyData['id'],
                            [
                                'property_value' => $propertyData['pivot']['property_value'],
                            ]
                        );
                        $requestPropertyIds[] = $propertyData['id'];
                    } else {
                        // If ID is null, add a new property to the database and also create a new pivot
                        Log::info("Adding new property to the database and creating new pivot with data: ", $propertyData['pivot']);

                        // Create the new property
                        $newProperty = Property::create([
                            'name' => $propertyData['name'],
                            'work_space_id' => $product->work_space_id,
                            'values' => $propertyData['pivot']['property_value'],
                        ]);

                        // Attach the new property to the product with the pivot data
                        $product->properties()->attach(
                            $newProperty->id,
                            [
                                'property_value' => $propertyData['pivot']['property_value'],
                            ]
                        );

                        Log::info("New property added with ID: " . $newProperty->id);
                        Log::info("New property added with value: " . $propertyData['pivot']['property_value']);
                        $requestPropertyIds[] = $newProperty->id;
                    }
                } else {
                    Log::error("Invalid property data: ", $propertyData);
                }
            }

            // Get currently attached property IDs
            $currentPropertyIds = $product->properties->pluck('id')->toArray();

            // Find property IDs that need to be detached
            $propertyIdsToDetach = array_diff($currentPropertyIds, $requestPropertyIds);

            if (!empty($propertyIdsToDetach)) {
                $product->properties()->detach($propertyIdsToDetach);
                Log::info("Detached properties with IDs: " . implode(', ', $propertyIdsToDetach));
            }
        }

        // Return a response
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }


    public function destroy($id)
    {
        // Find the product by its ID
        $product = Product::findOrFail($id);

        Gate::authorize('destroy-product', $product);

        // Soft delete the product
        $product->delete();

        return redirect('/products');
    }

    public function bulkDelete()
    {
        Gate::authorize('bulkDelete', [Product::class, request('product_ids')]);

        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
        ]);

        // Delete selected products
        $woocommerce = new WooCommerceManager;
        $woocommerce->deleteProductsFromAll($validatedData['product_ids']);
        Product::whereIn('id', $validatedData['product_ids'])->delete();

        return redirect()->back();
    }

    public function bulkDiscount(Request $request)
    {
        Gate::authorize('bulkUpdate', [Product::class, request('product_ids')]);

        //validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
            'discount' => ['required', 'numeric', 'between:0,100', 'min:0'], //0% koritng= geen koritng meer
            'cents' => ['nullable', 'numeric', 'digits_between:0,2', 'Integer', 'min:0'],
            'round' => ['required', 'boolean']
        ]);

        // Apply the discount to each product
        if ($validatedData['discount'] != 0) {
            if ($validatedData['round']) {
                $products = [];
                $failedProducts = [];
                foreach ($validatedData['product_ids'] as $productId) {
                    $product = Product::findOrFail($productId);
                    $discount = $product->price - $product->price / 100 * $validatedData['discount']; //bereken de korting op bassis van het gegeven percentage
                    $discount = round($discount, 0);
                    $discount += $validatedData['cents'] / 100;
                    $product->discount = $discount;
                    //fitler alle producten die duurder zijn geworden door afronden.
                    if ($product->price <= $discount) {
                        array_push($failedProducts, $product);
                    } else {
                        array_push($products, $product);
                    }
                }
                foreach ($products as $product) {
                    $product->save();
                }
                //als er errors zijn redirect met errors
                if (Count($failedProducts) > 0) {
                    redirect('/products')->with('discountErrors', $failedProducts);
                }
            } else {
                foreach ($validatedData['product_ids'] as $productId) {
                    $product = Product::findOrFail($productId);
                    $discount = $product->price - $product->price / 100 * $validatedData['discount']; //bereken de korting op bassis van het gegeven percentage
                    $product->discount = $discount;
                    $product->save();
                }
            }
        } else {
            foreach ($validatedData['product_ids'] as $productId) {
                $product = Product::findOrFail($productId);
                $product->discount = null;
                $product->save();
            }
        }
        $products = Product::whereIn('id', $validatedData['product_ids'])->get();
        $woocommerce = new WooCommerceManager();
        $woocommerce->uploadOrUpdateProductsSalesChannels($products->pluck('id')->toArray());

        return redirect()->back();
    }

    //adds the "discount" even if product is more expansive
    public function bulkDiscountForce()
    {
        Gate::authorize('bulkUpdate', [Product::class, array_keys(request('product_ids'))]);

        $validatedData = request()->validate([
            'product_ids' => ['required', 'array', new ValidProductKeys],
            'product_ids.*' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($validatedData['product_ids'] as $productId => $discount) {
            $product = Product::findOrFail($productId);
            $product->discount = $discount;
            $product->save();
        }
        return redirect()->back();
    }

    public function bulkLinkSalesChannel(Request $request)
    {
        Log::info($request);
        //die();
        $productIds = isset($request['product_ids']) ? $request['product_ids'] : [];
        $salesChannels = isset($request['sales_channel_ids']) ? $request['sales_channel_ids'] : [];

        Gate::authorize('bulkSaleschannels', [Product::class, $productIds, $salesChannels]);

        //validate request
        $validatedData = $request->validate([
            'product_ids' => ['required', 'array', new ValidProductIds],
            'product_ids.*' => ['required', 'numeric'],
            'sales_channel_ids' => ['required', 'array'],
            'sales_channel_ids.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);

        // link saleschannels to product
        $products = Product::whereIn('id', $validatedData['product_ids'])->with('photos', 'locationZones', 'productSalesChannels', 'properties', 'categories')->get();
        $salesChannels = SalesChannel::whereIn('id', $validatedData['sales_channel_ids'])->get();
        $data = [];
        foreach ($products as $product) {
            foreach ($salesChannels as $salesChannel) {
                $productSalesChannel = [
                    'product_id' => $product->id,
                    'sales_channel_id' => $salesChannel->id
                ];
                array_push($data, $productSalesChannel);
            }
        }
        DB::table('products')->whereIn('id', $validatedData['product_ids'])->update(['updated_at' => now()]);
        DB::table('product_sales_channel')->insert($data);
        $woocommerce = new WooCommerceManager;
        foreach ($salesChannels as $salesChannel) {
            $woocommerce->uploadOrUpdateProductsSalesChannel($products, $salesChannel);
        }
        return redirect('/products');
    }

    public function bulkUnlinkSalesChannel()
    {
        $request = request();

        $productIds = isset($request['product_ids']) ? $request['product_ids'] : [];
        $salesChannels = isset($request['sales_channel_ids']) ? $request['sales_channel_ids'] : [];

        Gate::authorize('bulkSaleschannels', [Product::class, $productIds, $salesChannels]);

        // Validate request
        $validatedData = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
            'sales_channel_ids' => ['required', 'array'],
            'sales_channel_ids.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);


        //remove from the saleschannel
        $woocommerce = new WooCommerceManager;
        foreach ($validatedData['sales_channel_ids'] as $salesChannel) {
            $salesChannel = SalesChannel::findOrFail($salesChannel);
            $woocommerce->deleteProductsFromSalesChannel($validatedData['product_ids'], $salesChannel);
        }
        $products = Product::whereIn('id', $validatedData['product_ids'])->get();
        foreach ($products as $product) {
            $product->touch();
        }
        // Unlink sales channels from products
        ProductSalesChannel::whereIn('product_id', $validatedData['product_ids'])
            ->whereIn('sales_channel_id', $validatedData['sales_channel_ids'])
            ->delete();


        return redirect()->back();
    }

    public function bulkEnableBackorders()
    {
        Gate::authorize('bulkUpdate', [Product::class, request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders' => true]);

        $woocommerce = new WooCommerceManager();
        $woocommerce->uploadOrUpdateProductsSalesChannels( $validatedData['product_ids']);

        return redirect()->back();
    }

    public function bulkDisableBackorders()
    {
        Gate::authorize('bulkUpdate', [Product::class, request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders' => false]);
        $woocommerce = new WooCommerceManager();
        $woocommerce->uploadOrUpdateProductsSalesChannels( $validatedData['product_ids']);

        return redirect()->back();
    }

    public function bulkEnableCommunicateStock()
    {
        Gate::authorize('bulkUpdate', [Product::class, request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock' => true]);
        $woocommerce = new WooCommerceManager();
        $woocommerce->uploadOrUpdateProductsSalesChannels( $validatedData['product_ids']);

        return redirect()->back();
    }

    public function bulkDisableCommunicateStock()
    {
        Gate::authorize('bulkUpdate', [Product::class, request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock' => false]);
        $woocommerce = new WooCommerceManager();
        $woocommerce->uploadOrUpdateProductsSalesChannels( $validatedData['product_ids']);

        return redirect()->back();
    }

    public function store(Request $request)
    {
        #region //authorize
        $salesChannels = isset($request['salesChannelIds']) ? $request['salesChannelIds'] : [];
        $categories = isset($request['categories']) ? array_keys($request['categories']) : [];
        $properties = isset($request['properties']) ? array_keys($request['properties']) : [];
        $location_zones = isset($request['location_zones']) ? array_keys($request['location_zones']) : [];

        Gate::authorize('store', [
            Product::class,
            $salesChannels,
            $categories,
            $properties,
            $location_zones
        ]);

        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspace = $request['workspace'];
        } else {
            $workspace = Auth::user()->work_space_id;
        }
        #endregion

        #region //validate
        $saleschannelAttributes = $this->validateSalesChannelAttributes($request);
        $forOnline = false;
        if (Count($saleschannelAttributes['salesChannels']) > 0) {
            $forOnline = true;
        }
        $validationRules = [];
        $validationRules += $this->validateProductAttributes($forOnline);
        $validationRules += $this->validateCategoryAttributes();
        $validationRules += $this->validatePhotoAttributes($forOnline);
        $validationRules += $this->validatePropertyAttributes();
        $validationRules += $this->validateInventoryAttributes();
        $validationRules += $this->validateNewProperiesAttributes();
        $attributes = $request->validate($validationRules);
        #endregion
        //create product and links
        $product = $this->createProduct($attributes);
        $this->linkCategoriesToProduct($product, $attributes);
        $this->uploadAndLinkPhotosToProduct($product, $request);
        $this->linkPropertiesToProduct($product, $attributes);
        $this->createAndLinkProperties($product, $attributes, $workspace);
        $this->createInventories($product, $request);

        //link sales channels if there are any.
        if ($forOnline) {
            $this->linkSalesChannelsToProduct($product, $saleschannelAttributes);
        }

        //return to product page
        return redirect('/products');
    }

    public function export()
    {
        return Excel::download(new ProductsExport(null), 'products.csv');
    }

    protected function validateNewProperiesAttributes(): array
    {
        return [
            'newProperties' => ['nullable', 'array'],
            'newProperties.*' => ['nullable', 'array'],
            'newProperties.*.value' => ['required', 'string'],
            'newProperties.*.name' => ['required', 'string'],
            'newProperties.*.type' => ['required', Rule::in(['singleselect', 'multiselect', 'number', 'bool', 'text'])],
            'newProperties.*.options' => ['nullable', 'array'],
            'newProperties.*.options.*' => ['required', 'string']
        ];
    }

    public function archive(Request $request)
    {
        $request->validate([
            'workspace' => ['required', new ValidWorkspaceKeys]
        ]);
        $perPage = $request->input('perPage', 20);
        $products = Product::where('work_space_id', $request['workspace'])
            ->with('photos', 'locationZones', 'productSalesChannels', 'childProducts', 'categories')
            ->whereNull('parent_product_id')
            ->whereNotNull('deleted_at')
            ->withTrashed()
            ->paginate($perPage)
            ->withQueryString();
        $salesChannels = SalesChannel::where('work_space_id', $request['workspace']);
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];

        $results = [
            'products' => $products,
            'perPage' => $perPage,
            'search' => $request['search'],
            'selectedCategories' => $request['categories'],
            'sidenavActive' => 'archive',
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
            'salesChannels' => $salesChannels,
        ];
        return view('product.archive', $results);
    }

    public function restore(Request $request)
    {
        $attributes = $request->validate([
            'products' => ['array', 'required'],
            'products.*' => ['numeric', 'required']
        ]);
        Product::withTrashed()->whereIn('id', $attributes['products'])->restore();
        return redirect()->back();
    }

    public function forceDelete(Request $request)
    {
        $attributes = $request->validate([
            'products' => ['array', 'required'],
            'products.*' => ['numeric', 'required']
        ]);
        Product::withTrashed()->whereIn('id', $attributes['products'])->forceDelete();
        return redirect()->back();
    }

    protected function createProduct($attributes): Product
    {
        return Product::create([
            'work_space_id' => Auth::user()->work_space_id,
            'sku' => isset($attributes['sku']) ? $attributes['sku'] : null ,
            'ean' => isset($attributes['ean']) ? $attributes['ean'] : null,
            'title' => isset($attributes['title']) ? $attributes['title'] : null,
            'price' => isset($attributes['price']) ? $attributes['price'] : null,
            'long_description' => isset($attributes['long_description']) ? $attributes['long_description'] : null,
            'short_description' =>isset($attributes['short_description']) ? $attributes['short_description'] : null,
            'backorders' => isset($attributes['backorders']) ? $attributes['backorders'] : null,
            'communicate_stock' => isset($attributes['communicate_stock']) ? $attributes['communicate_stock'] : null
        ]);
    }

    protected function createInventories($product, $request)
    {
        if($request->input('location_zones')){
            foreach ($request->input('location_zones') as $location_zone_id => $stock) {
                Inventory::create([
                    'product_id' => $product->id,
                    'location_zone_id' => $location_zone_id,
                    'stock' => $stock
                ]);
            }
        }
    }

    protected function validateProductAttributes(bool $forOnline)
    {
        if ($forOnline) {
            return [
                'sku' => ['required', 'max:255', 'unique:products,sku'],
                'ean' => ['digits:13', 'nullable', 'unique:products,ean'],
                'title' => ['required', 'max:255'],
                'price' => ['required', 'numeric'],
                'long_description' => ['max:32000', 'nullable'],
                'short_description' => ['max:32000', 'nullable'],
                'backorders' => ['required', 'numeric'],
                'communicate_stock' => ['required', 'numeric'],
                'discount' => ['nullable', 'numeric']
            ];
        } else {
            return [
                'sku' => ['nullable', 'max:255', 'unique:products,sku'],
                'ean' => ['digits:13', 'nullable', 'unique:products,ean'],
                'title' => ['nullable', 'max:255'],
                'price' => ['nullable', 'numeric'],
                'long_description' => ['max:32000', 'nullable'],
                'short_description' => ['max:32000', 'nullable'],
                'backorders' => ['nullable', 'numeric'],
                'communicate_stock' => ['nullable', 'numeric'],
                'discount' => ['nullable', 'numeric']
            ];
        }
    }

    protected function validateProductAttributesUpdate(bool $forOnline, int $productId)
    {
        if ($forOnline) {
            return [
                'sku' => ['required', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
                'ean' => ['digits:13', 'nullable', Rule::unique('products', 'ean')->ignore($productId)],
                'title' => ['required', 'max:255'],
                'price' => ['required', 'numeric'],
                'long_description' => ['max:32000', 'nullable'],
                'short_description' => ['max:32000', 'nullable'],
                'backorders' => ['nullable', 'numeric'],
                'communicate_stock' => ['nullable', 'numeric'],
                'discount' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'sku' => ['nullable', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
                'ean' => ['digits:13', 'nullable', Rule::unique('products', 'ean')->ignore($productId)],
                'title' => ['nullable', 'max:255'],
                'price' => ['nullable', 'numeric'],
                'long_description' => ['max:32000', 'nullable'],
                'short_description' => ['max:32000', 'nullable'],
                'backorders' => ['nullable', 'numeric'],
                'communicate_stock' => ['nullable', 'numeric'],
                'discount' => ['nullable', 'numeric'],
            ];
        }
    }

    protected function validateInventoryAttributes()
    {
        return [
            'location_zones' => ['nullable', 'array', new ValidLocationZoneKeys],
            'location_zones.*' => ['required', 'numeric']
        ];
    }

    protected function validateCategoryUpdate()
    {
        return [
            'categories' => ['nullable', 'array', new VallidCategoryKeys],
            'categories.*' => ['required', 'numeric']
        ];
    }

    protected function validateSalesChannelAttributesUpdate($request)
    {
        $attributes = $request->validate([
            'salesChannels' => ['array', 'nullable', new ValidSalesChannelKeys],
            'salesChannels.*' => ['required', 'array'],
            'salesChannels.*.title' => ['nullable', 'max:32000'],
            'salesChannels.*.short_description' => ['nullable', 'max:32000'],
            'salesChannels.*.long_description' => ['nullable', 'max:32000'],
            'salesChannels.*.price' => ['nullable', 'numeric'],
            'salesChannels.*.discount' => ['nullable', 'numeric'],
            'salesChannels.*.categories' => ['nullable', 'array', new VallidCategoryKeys],
            'salesChannels.*.categories.*' => ['required', 'numeric'],
            'salesChannels.*.properties' => ['nullable', 'array', new ValidPropertyKeys, new ValidPropertyOptions],
            'salesChannels.*.properties.*' => ['required'],
            'salesChannelIds' => ['array', 'nullable'],
            'salesChannelIds.*' => ['required', Rule::exists('sales_channels', 'id')]
        ]);
        if ($request['salesChannels'] == null) {
            $attributes['salesChannels'] = [];
        }
        return $attributes;
    }

    protected function updateCategories($productId, $categoryData)
    {
        // Get all existing CategoryProduct entries for the given product
        $existingCategoryProducts = CategoryProduct::where('product_id', $productId)->get();

        // Create an array to store the IDs of existing categories
        $existingCategoryIds = $existingCategoryProducts->pluck('category_id')->toArray();

        // Loop through the new category data
        foreach ($categoryData as $categoryId => $isPrimary) {
            // Check if the category exists in the existing CategoryProduct entries
            if (!in_array($categoryId, $existingCategoryIds)) {
                // If the category does not exist, create a new CategoryProduct entry
                CategoryProduct::create([
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                    'primary' => $isPrimary
                ]);
            } else {
                // If the category exists, update its primary status if necessary
                $existingCategoryProduct = $existingCategoryProducts->where('category_id', $categoryId)->first();
                if ($existingCategoryProduct->primary != $isPrimary) {
                    $existingCategoryProduct->update(['primary' => $isPrimary]);
                }
                // Remove the category ID from the existing IDs array
                unset($existingCategoryIds[array_search($categoryId, $existingCategoryIds)]);
            }
        }

        // Delete CategoryProduct entries for categories not present in the new data
        CategoryProduct::where('product_id', $productId)
            ->whereIn('category_id', $existingCategoryIds)
            ->delete();
    }

    protected function updateSalesChannels($productId, $salesChannelData)
    {
        // Get all existing CategoryProduct entries for the given product
        $existingProductSalesChannels = ProductSalesChannel::where('product_id', $productId)->get();

        // Create an array to store the IDs of existing SalesChannels
        $existingSalesChannelIds = $existingProductSalesChannels->pluck('sales_channel_id')->toArray();

        $salesChannelIds = isset($salesChannelData['salesChannelIds']) ? $salesChannelData['salesChannelIds'] : [];

        // Loop through the new SalesChannel data
        foreach ($salesChannelIds as $salesChannelId) {
            // Check if the SalesChannel exists in the existing ProductSalesChannel entries
            if (!in_array($salesChannelId, $existingSalesChannelIds)) {
                // If the SalesChannel does not exist, create a new ProductSalesChannel entry
                $attributes = ['product_id' => $productId, 'sales_channel_id' => $salesChannelId];
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['title'])) {
                    $attributes += ['title' => $salesChannelData['salesChannels'][$salesChannelId]['title']];
                }
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['short_description'])) {
                    $attributes += ['short_description' => $salesChannelData['salesChannels'][$salesChannelId]['short_description']];
                }
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['long_description'])) {
                    $attributes += ['long_description' => $salesChannelData['salesChannels'][$salesChannelId]['long_description']];
                }
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['price'])) {
                    $attributes += ['price' => $salesChannelData['salesChannels'][$salesChannelId]['price']];
                }
                //create the saleschannel
                $productSalesChannel = ProductSalesChannel::create($attributes);

                //after creating the saleschannel add categories to it. (if there are categoreis to add)
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['categories'])) {
                    foreach ($salesChannelData['salesChannels'][$salesChannelId]['categories'] as $category => $isPrimary) {
                        CategoryProductSalesChannel::create([
                            'category_id' => $category,
                            'product_sales_channel_id' => $productSalesChannel->id,
                            'primary' => $isPrimary
                        ]);
                    }
                }
                //after createing the saleschannel add properties to it (if there are properties to add)
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['properties'])) {
                    foreach ($salesChannelData['salesChannels'][$salesChannelId]['properties'] as $property => $value) {
                        $prop = Property::where('id', $property)->get()->first();
                        if ($prop->type === 'multislect') {
                            $value = explode(',', $value);
                        }
                        ProductSalesChannelProperty::create([
                            'property_id' => $property,
                            'product_sales_channel_id' => $productSalesChannel->id,
                            'value' => json_encode(['value' => $value])
                        ]);
                    }
                }
            } else {
                // If the SalesChannel does  exist, update the existing entry
                $productSalesChannel = ProductSalesChannel::where('sales_channel_id', $salesChannelId)->where('product_id', $productId)->first();
                $productSalesChannel->update([
                    'title' => $salesChannelData['salesChannels'][$salesChannelId]['title'],
                    'short_description' => $salesChannelData['salesChannels'][$salesChannelId]['short_description'],
                    'long_description' => $salesChannelData['salesChannels'][$salesChannelId]['long_description'],
                    'price' => $salesChannelData['salesChannels'][$salesChannelId]['price']
                ]);
                //update categories
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['categories'])) {
                    $this->updateSalesChannelCategories($productSalesChannel->id, $salesChannelData['salesChannels'][$salesChannelId]['categories']);
                }
                //update properties
                if (isset($salesChannelData['salesChannels'][$salesChannelId]['properties'])) {
                    $this->updateSalesChannelProperties($productSalesChannel->id, $salesChannelData['salesChannels'][$salesChannelId]['properties']);
                }
                unset($existingSalesChannelIds[array_search($salesChannelId, $existingSalesChannelIds)]);
            }
        }
        // Delete ProductSalesChannel entries for SalesChannels not present in the new data
        ProductSalesChannel::where('product_id', $productId)
            ->whereIn('sales_channel_id', $existingSalesChannelIds)
            ->delete();
    }

    protected function updateSalesChannelCategories($productSalesChannelId, array $categories)
    {
        // Get all existing links entries for the given ProductSaleschannel
        $existingCategoryLinks = CategoryProductSalesChannel::where('product_sales_channel_id', $productSalesChannelId)->get();

        // Create an array to store the IDs of existing categorylinks
        $existingCategoryIds = $existingCategoryLinks->pluck('category_id')->toArray();

        // Loop through the categories
        foreach ($categories as $categoryId => $isPrimary) {
            // Check if the category exists in the existing CategoryProductSalesChannels entries
            if (!in_array($categoryId, $existingCategoryIds)) {
                // If the category does not exist, create a new CategoryProductSalesChannels entry
                CategoryProductSalesChannel::create([
                    'product_sales_channel_id' => $productSalesChannelId,
                    'category_id' => $categoryId,
                    'primary' => $isPrimary
                ]);
            } else {
                // If the category exists, update its primary status if necessary
                $existingCategoryLink = $existingCategoryLinks->where('category_id', $categoryId)->first();
                if ($existingCategoryLink->primary != $isPrimary) {
                    $existingCategoryLink->update(['primary' => $isPrimary]);
                }
                // Remove the category ID from the existing IDs array
                unset($existingCategoryIds[array_search($categoryId, $existingCategoryIds)]);
            }
        }

        // Delete CategoryProduct entries for categories not present in the new data
        CategoryProductSalesChannel::where('product_sales_channel_id', $productSalesChannelId)
            ->whereIn('category_id', $existingCategoryIds)
            ->delete();
    }

    protected function updateSalesChannelProperties($productSalesChannelId, array $properties)
    {
        // Get all existing ProductProperty entries for the given product
        $existingProductSalesChannelProperties = ProductSalesChannelProperty::where('product_sales_channel_id', $productSalesChannelId)->get();

        // Create an array to store the IDs of existing properties
        $existingPropertyIds = $existingProductSalesChannelProperties->pluck('property_id')->toArray();

        // Loop through the new property data
        foreach ($properties as $propertyId => $propertyValue) {
            // Check if the property exists in the existing ProductProperty entries
            if (!in_array($propertyId, $existingPropertyIds)) {
                // If the property does not exist, create a new ProductProperty entry
                ProductSalesChannelProperty::create([
                    'product_sales_channel_id' => $productSalesChannelId,
                    'property_id' => $propertyId,
                    'prop_value' => json_encode(['value' => $propertyValue])
                ]);
            } else {
                // If the property exists, update its value
                $existingProductProperty = $existingProductSalesChannelProperties->where('property_id', $propertyId)->first();
                $existingProductProperty->update(['property_value' => json_encode(['value' => $propertyValue])]);

                // Remove the property ID from the existing IDs array
                unset($existingPropertyIds[array_search($propertyId, $existingPropertyIds)]);
            }
        }

        // Delete ProductProperty entries for properties not present in the new data
        ProductSalesChannelProperty::where('product_sales_channel_id', $productSalesChannelId)
            ->whereIn('property_id', $existingPropertyIds)
            ->delete();
    }

    protected function updateProperties($productId, $propertyData)
    {
        // Get all existing ProductProperty entries for the given product
        $existingProductProperties = ProductProperty::where('product_id', $productId)->get();

        // Create an array to store the IDs of existing properties
        $existingPropertyIds = $existingProductProperties->pluck('property_id')->toArray();

        // Loop through the new property data
        foreach ($propertyData as $propertyId => $propertyValue) {
            // Check if the property exists in the existing ProductProperty entries
            if (!in_array($propertyId, $existingPropertyIds)) {
                // If the property does not exist, create a new ProductProperty entry
                ProductProperty::create([
                    'product_id' => $productId,
                    'property_id' => $propertyId,
                    'property_value' => json_encode(['value' => $propertyValue])
                ]);
            } else {
                // If the property exists, update its value
                $existingProductProperty = $existingProductProperties->where('property_id', $propertyId)->first();
                $existingProductProperty->update(['property_value' => json_encode(['value' => $propertyValue])]);

                // Remove the property ID from the existing IDs array
                unset($existingPropertyIds[array_search($propertyId, $existingPropertyIds)]);
            }
        }

        // Delete ProductProperty entries for properties not present in the new data
        ProductProperty::where('product_id', $productId)
            ->whereIn('property_id', $existingPropertyIds)
            ->delete();
    }

    protected function linkPropertiesToProduct($product, $attributes)
    {
        if(isset($attributes['properties'])){
            foreach ($attributes['properties'] as $propertyId => $propertyValue) {
                ProductProperty::create([
                    'product_id' => $product->id,
                    'property_id' => $propertyId,
                    'property_value' => json_encode(['value' => $propertyValue])
                ]);
            }
        }
    }

    protected function createAndLinkProperties($product, $attributes, $workspace)
    {
        if (isset($attributes['newProperties'])) {
            foreach ($attributes['newProperties'] as $property) {
                if (!isset($property['options'])) {
                    $property['options'] = [];
                }
                $values = json_encode(['type' => $property['type'], 'options' => $property['options']]);
                $newProperty = Property::create([
                    'name' => $property['name'],
                    'work_space_id' => $workspace,
                    'values' => $values
                ]);
                ProductProperty::create([
                    'product_id' => $product->id,
                    'property_id' => $newProperty->id,
                    'property_value' => json_encode(['value' => $property['value']])
                ]);
            }
        }
    }

    public function archiveProducts(Request $request)
    {
        $selectedProducts = $request->input('selectedProducts');

        if (!$selectedProducts || !is_array($selectedProducts)) {
            return response()->json(['error' => 'Invalid request body'], 400);
        }

        // Archive the selected products
        foreach ($selectedProducts as $productId) {
            $product = Product::find($productId);
            if ($product) {
                if ($product->archived) {
                    continue;
                }
                $product->archived = true;
                $product->save();
            }
        }

        return response()->json(['message' => 'Product were archived succesfully'], 200);
    }

    public function switchStatus(Request $request)
    {
        $selectedProducts = $request->input('selectedProducts');

        if (!$selectedProducts || !is_array($selectedProducts)) {
            return response()->json(['error' => 'Invalid request body'], 400);
        }

        // Switch the status of the selected products
        foreach ($selectedProducts as $productId) {
            $product = Product::find($productId);
            if ($product) {
                // Toggle the status
                $product->status = !$product->status;
                $product->save();
            }
        }

        return response()->json(['message' => 'Product status were switched successfully'], 200);
    }

    public function deleteById($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function deleteProducts(Request $request)
    {
        $selectedProducts = $request->input('selectedProducts');


        if (!$selectedProducts || !is_array($selectedProducts)) {
            return response()->json(['error' => 'Invalid request body'], 400);
        }

        // Delete the selected products
        foreach ($selectedProducts as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->delete();
            }
        }

        return response()->json(['message' => 'Product were deleted successfully'], 200);
    }

    public function duplicate($id) {
        // Find the product by its ID
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Duplicate the product
        $newProduct = $product->replicate();


        $newProduct->sku = null;
        $newProduct->ean = null;

        // Ensure unique title by appending a unique suffix
        $newTitle = $product->title . ' (copy)';
        while (Product::where('title', $newTitle)->exists()) {
            $newTitle = $product->title . ' (copy)' . '-' . Str::random(5);
        }
        $newProduct->title = $newTitle;

        // Save the new product
        $newProduct->save();

        // Return the new product as a JSON response
        return response()->json($newProduct, 201);
    }


    public function archiveById($id) {
        // Find the product by its ID
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->archived = true;
        $product->save();

        // Return the new product as a JSON response
        return response()->json($product, 201);
    }

    public function exportById($id) {
        // Find the product by its ID
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $filename = 'products-' . Str::random(20) . '.csv';
        while (Storage::disk('public')->exists($filename)) {
            $filename = 'products-' . Str::random(20) . '.csv';
        }

        if(Excel::store(new ProductsExport($id), $filename, 'public')) {
            return response()->json(['message' => 'Product exported successfully', 'filename' => $filename], 200);
        } else {
            return response()->json(['error' => 'Failed to export product'], 500);
        }
    }

}
