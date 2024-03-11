<?php

namespace App\Http\Controllers;

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
use App\Rules\VallidCategoryKeys;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseProductController
{
    //TODO
    public function index(Request $request)
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }


        $perPage = $request->input('perPage', 15);

        return view('product.index', [
            'products' => Product::with('photos', 'locationZones', 'salesChannels.sales', 'childProducts', 'categories')->withExists(['salesChannels'])->whereNull('parent_product_id')->paginate($perPage),
            'categories' => Category::with(['child_categories'])->whereNull('parent_category_id')->get(),
            'properties' => $properties,
            'sales_channels' => SalesChannel::all(),
            'perPage' => $perPage
        ]);
    }

    public function create()
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }
        return view('product.create', [
            'categories' => Category::with(['child_categories'])->whereNull('parent_category_id')->get(),
            'properties' => $properties,
            'locations' => InventoryLocation::with(['location_zones'])->get(),
            'salesChannels' => SalesChannel::all()
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
            'categories' => Category::with(['child_categories'])->whereNull('parent_category_id')->get()
        ]);
    }

    public function destroy($id)
    {
        // Find the product by its ID
        $product = Product::findOrFail($id);

        // Soft delete the product
        $product->delete();

        // Redirect back to the product index page with a success message
        return redirect('/products');
    }

    public function bulkDelete()
    {
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
        //validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array', new ValidProductKeys],
            'product_ids.*' => ['required', 'array'],
            'product_ids.*.discount' => ['required', 'numeric']
        ]);
        // Apply the discount to each product
        foreach ($validatedData['product_ids'] as $productId => $discount) {
            $product = Product::findOrFail($productId);
            $product->discount = $discount['discount'];
            $product->save();
        }
        return redirect('/products');
    }

    public function bulkLinkSalesChannel()
    {
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
        //validate
        $saleschannelAttributes = $this->validateSalesChannelAttributes($request);
        $forOnline = false;
        if (Count($saleschannelAttributes['salesChannels']) > 0 || ProductSalesChannel::where('product_id', $product->id)->exists()) {
            $forOnline = true;
        }
        $attributes = $request->validate($this->validateProductAttributesUpdate($forOnline, $product->id));

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
        $this->updateCategories($product->id, $attributes['categories']);

        return redirect()->back();
    }

    public function store()
    {
        $request = request();
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
                //category validation
                'categories' => ['nullable', 'array', new VallidCategoryKeys],
                'categories.*' => ['required', 'numeric']
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
                //category validation
                'categories' => ['nullable', 'array', new VallidCategoryKeys],
                'categories.*' => ['required', 'numeric']
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
}
