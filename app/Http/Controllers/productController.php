<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Rules\ValidLocationZoneKeys;
use App\Rules\ValidProductKeys;
use App\Rules\ValidSalesChannelKeys;
use App\Rules\VallidCategoryKeys;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseProductController
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 20);

        $products = 

        $results = [
            'products' => Product::with('photos', 'locationZones', 'salesChannels.sales', 'childProducts', 'categories')
                ->withExists(['salesChannels'])
                ->where('work_space_id', Auth::user()->work_space_id)
                ->whereNull('parent_product_id')
                ->filter(request(['search', 'categories', 'orderByInput']))
                ->paginate($perPage)
                ->withQueryString()
                ,
            // 'properties' => Property::all(),
            'salesChannels' => SalesChannel::where('work_space_id', Auth::user()->work_space_id)->get(),
            'perPage' => $perPage,
            'search' => request('search'),
            'selectedCategories' => request('categories'),
            'orderBy' => request('orderByInput'),
            'sidenavActive' => 'products',
            'categories' => Category::with('child_categories_recursive')->whereNull('parent_category_id')->where('work_space_id', Auth::user()->work_space_id)->get()
        ];
        return view('product.index', $results);
    }

    public function create()
    {
        return view('product.create', [
            'categories' => Category::with(['child_categories'])->whereNull('parent_category_id')->where('work_space_id', Auth::user()->work_space_id)->get(),
            'properties' => Property::where('work_space_id', Auth::user()->work_space_id)->get(),
            'locations' => InventoryLocation::with(['location_zones'])->where('work_space_id', Auth::user()->work_space_id)->get(),
            'sidenavActive' => 'products',
            'salesChannels' => SalesChannel::where('work_space_id', Auth::user()->work_space_id)->get()
        ]);
    }

    public function show(Product $product)
    {
        //if the product is a variant product return 404
        if ($product->parent_product_id != null) {
            return abort(404);
        }
        foreach ($product->properties as $prop) {
            $prop->pivot->property_value = json_decode($prop->pivot->property_value);
        }
        return view('product.show', [
            'product' => $product,
            'categories' => Category::with('child_categories_recursive')->whereNull('parent_category_id')->get(),
            'sidenavActive' => 'products',
            'salesChannels' => SalesChannel::where('work_space_id', Auth::user()->work_space_id)->get(),
            'selectedSalesChannels' => ProductSalesChannel::where('product_id', $product->id)->get(),
        ]);
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
        Gate::authorize('bulk-products', [request('product_ids')]);

        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
        ]);

        // Delete selected products
        Product::whereIn('id', $validatedData['product_ids'])->delete();

        return redirect('/products');
    }


    public function bulkDiscount()
    {
        Gate::authorize('bulk-products', [request('product_ids')]);
        //validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
            'discount' => ['required', 'numeric' , 'between:0,101'], //0% koritng= geen koritng meer
            'cents' => ['nullable', 'numeric', 'between:0,100', 'Integer'],
            'round' => ['required', 'boolean']
        ]);

        // Apply the discount to each product
        if($validatedData['discount'] != 0){
            if($validatedData['round']){

            }else{
                foreach ($validatedData['product_ids'] as $productId) {
                    $product = Product::findOrFail($productId);
                    $discount = $product->price - $product->price / 100 * $validatedData['discount']; //bereken de korting op bassis van het gegeven percentage
                    $product->discount = $discount;
                    $product->save();
                }
            }
        }else{
            foreach ($validatedData['product_ids'] as $productId) {
                $product = Product::findOrFail($productId);
                $product->discount = null;
                $product->save();
            }
        }
        
        
        return redirect()->back();
    }

    public function bulkLinkSalesChannel()
    {
        Gate::authorize('bulk-saleschannel-products');

        //validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
            'sales_channel_ids' => ['required', 'array'],
            'sales_channel_ids.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);

        // link saleschannels to product
        foreach ($validatedData['product_ids'] as $product) {
            foreach ($validatedData['sales_channel_ids'] as $salesChannel) {
                ProductSalesChannel::create([
                    'product_id' => $product,
                    'sales_channel_id' => $salesChannel
                ]);
            }
        }
        return redirect('/products');
    }

    public function bulkUnlinkSalesChannel()
    {
        Gate::authorize('bulk-saleschannel-products');

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')],
            'sales_channel_ids' => ['required', 'array'],
            'sales_channel_ids.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);

        // Unlink sales channels from products
        ProductSalesChannel::whereIn('product_id', $validatedData['product_ids'])
            ->whereIn('sales_channel_id', $validatedData['sales_channel_ids'])
            ->delete();
        return redirect()->back();
    }

    public function bulkEnableBackorders()
    {
        Gate::authorize('bulk-products', [request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders' => true]);

        return redirect()->back();
    }

    public function bulkDisableBackorders()
    {
        Gate::authorize('bulk-products', [request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders' => false]);

        return redirect()->back();
    }

    public function bulkEnableCommunicateStock()
    {
        Gate::authorize('bulk-products', [request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock' => true]);

        return redirect()->back();
    }

    public function bulkDisableCommunicateStock()
    {
        Gate::authorize('bulk-products', [request('product_ids')]);

        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock' => false]);

        return redirect()->back();
    }

    public function update(Product $product)
    {
        $request = request();

        //authorize
        $salesChannels = isset($request['salesChannelIds'])  ? $request['salesChannelIds'] : [];
        $categories = isset($request['categories']) ? array_keys($request['categories']) : [];
        $properties = isset($request['properties']) ? array_keys($request['properties']) : [];
        $location_zones = isset($request['location_zones']) ? array_keys($request['location_zones']) : [];
        Gate::authorize('update-product', [
            $product,
            $salesChannels,
            $categories,
            $properties,
            $location_zones
        ]);


        //validate
        $saleschannelAttributes = $this->validateSalesChannelAttributesUpdate($request);
        $forOnline = false;
        if(Count($saleschannelAttributes['salesChannels'])  >  0){
            $forOnline = true;
        }

        $validationRules = $this->validateProductAttributesUpdate($forOnline, $product->id);
        $validationRules += $this->validateCategoryUpdate();
        

        $attributes = $request->validate($validationRules);

        if (!isset($attributes['backorders'])) {
            $attributes['backorders'] = false;
        }
        if (!isset($attributes['communicate_stock'])) {

            $attributes['communicate_stock'] = false;
        }
        //update product
        $product->update([
            'sku' => $attributes['sku'],
            'ean' => $attributes['ean'],
            'title' => $attributes['title'],
            'price' => $attributes['price'],
            'long_description' => $attributes['long_description'],
            'short_description' => $attributes['short_description'],
            'backorders' => $attributes['backorders'],
            'communicate_stock' => $attributes['communicate_stock'],
            'discount' => $attributes['discount']
        ]);

        if(!isset($attributes['categories'])){
            $attributes['categories']=[];
        }      
        $this->updateCategories($product->id, $attributes['categories']);

        $this->updateSalesChannels($product->id, $saleschannelAttributes);
        

        return redirect()->back();
    }

    public function store()
    {
        $request = request();
        //authorize
        $salesChannels = isset($request['salesChannelIds'])  ? $request['salesChannelIds'] : [];
        $categories = isset($request['categories']) ? array_keys($request['categories']) : [];
        $properties = isset($request['properties']) ? array_keys($request['properties']) : [];
        $location_zones = isset($request['location_zones']) ? array_keys($request['location_zones']) : [];
        
        
        Gate::authorize('store-product', [
            $salesChannels,
            $categories,
            $properties,
            $location_zones
        ]);
        

        //validate
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
        $attributes =  $request->validate($validationRules);

        //create product and links
        $product = $this->createProduct($attributes);
        $this->linkCategoriesToProduct($product, $attributes);
        $this->uploadAndLinkPhotosToProduct($product, $request);
        $this->linkPropertiesToProduct($product, $request);
        $this->createInventories($product, $request);

        //link sales channels if there are any.
        if ($forOnline) {
            $this->linkSalesChannelsToProduct($product, $saleschannelAttributes);
        }

        //return to product page
        return redirect('/products');
    }

    protected function createProduct($attributes): Product
    {
        return Product::create([
            'work_space_id' => Auth::user()->work_space_id,
            'sku' => $attributes['sku'],
            'ean' => $attributes['ean'],
            'title' => $attributes['title'],
            'price' => $attributes['price'],
            'long_description' => $attributes['long_description'],
            'short_description' => $attributes['short_description'],
            'backorders' => $attributes['backorders'],
            'communicate_stock' => $attributes['communicate_stock']
        ]);
    }

    protected function createInventories($product, $request)
    {
        foreach ($request->input('location_zones') as $location_zone_id => $stock) {
            Inventory::create([
                'product_id' => $product->id,
                'location_zone_id' => $location_zone_id,
                'stock' => $stock
            ]);
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
            'categories.*' => ['required' , 'numeric']
        ];
    }

    protected function validateSalesChannelAttributesUpdate($request){
        $attributes = $request->validate([
            'salesChannels' => ['array', 'nullable', new ValidSalesChannelKeys],
            'salesChannels.*' => ['required', 'array'],
            'salesChannels.*.title' => ['nullable', 'max:32000'],
            'salesChannels.*.short_description' => ['nullable', 'max:32000'],
            'salesChannels.*.long_description' => ['nullable', 'max:32000'],
            'salesChannels.*.price' => ['nullable', 'numeric'],
            'salesChannels.*.discount' => ['nullable', 'numeric'],
            'salesChannelIds' => ['array', 'nullable'],
            'salesChannelIds' => ['nullable', Rule::exists('sales_channels', 'id')]
        ]);
        if ($request['salesChannels'] == null) {
            $attributes['salesChannels'] = [];
        }
        return $attributes;
    }

    function updateCategories($productId, $categoryData)
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

    function updateSalesChannels($productId, $salesChannelData)
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
                if(isset($salesChannelData['salesChannels'][$salesChannelId]['title'])){
                    $attributes += ['title' => $salesChannelData['salesChannels'][$salesChannelId]['title']];
                }
                if(isset($salesChannelData['salesChannels'][$salesChannelId]['short_description'])){
                    $attributes += ['short_description' => $salesChannelData['salesChannels'][$salesChannelId]['short_description']];
                }
                if(isset($salesChannelData['salesChannels'][$salesChannelId]['long_description'])){
                    $attributes += ['long_description' => $salesChannelData['salesChannels'][$salesChannelId]['long_description']];
                }
                if(isset($salesChannelData['salesChannels'][$salesChannelId]['price'])){
                    $attributes += ['price' => $salesChannelData['salesChannels'][$salesChannelId]['price']];
                }
                ProductSalesChannel::create(
                    $attributes
                );           
            } else {
                $productSalesChannel = ProductSalesChannel::where('sales_channel_id', $salesChannelId)->where('product_id', $productId)->first();
                $productSalesChannel->update([
                    'title' => $salesChannelData['salesChannels'][$salesChannelId]['title'],
                    'short_description' => $salesChannelData['salesChannels'][$salesChannelId]['short_description'],
                    'long_description' => $salesChannelData['salesChannels'][$salesChannelId]['long_description'],
                    'price' =>  $salesChannelData['salesChannels'][$salesChannelId]['price']
                ]);
                unset($existingSalesChannelIds[array_search($salesChannelId, $existingSalesChannelIds)]);
            }
        }
        
        // Delete ProductSalesChannel entries for SalesChannels not present in the new data
        ProductSalesChannel::where('product_id', $productId)
            ->whereIn('sales_channel_id', $existingSalesChannelIds)
            ->delete();
    }
}
