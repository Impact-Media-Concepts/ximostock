<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\Photo;
use App\Models\PhotoProduct;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\Sale;
use App\Rules\ValidProductKeys;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $properties = Property::all();

        foreach ($properties as $prop) {
            $prop->values = json_decode($prop->values);
        }

        return view('product.index', [
            'products' => Product::with('photos', 'locationZones', 'salesChannels.sales', 'childProducts', 'categories')->withExists(['salesChannels'])->whereNull('parent_product_id')->get(),
            'categories' => Category::with(['child_categories'])->whereNull('parent_category_id')->get(),
            'properties' => $properties,
            'sales_channels' => SalesChannel::all()
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
            'product' => $product
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

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders'=> true]);

        return redirect()->back();
    }

    public function bulkDisableBackorders()
    {
        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['backorders'=> false]);

        return redirect()->back();
    }

    public function bulkEnableCommunicateStock()
    {
        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock'=> true]);

        return redirect()->back();
    }

    public function bulkDisableCommunicateStock()
    {
        // Validate request
        $validatedData = request()->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'numeric', Rule::exists('products', 'id')]
        ]);

        Product::whereIn('id', $validatedData['product_ids'])->update(['communicate_stock'=> false]);

        return redirect()->back();
    }


    // TODO
    public function update(Product $product)
    {
        $request = request();

        $attributes = $request->validate([
            'discount' => ['nullable', 'numeric']
        ]);
        $product->update($attributes);

        return redirect('/products');
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

    protected function linkCategoriesToProduct($product, $attributes)
    {
        //link primary
        CategoryProduct::create([
            'category_id' => $attributes['primaryCategory'],
            'product_id' => $product->id,
            'primary' => true
        ]);
        //link all other categories
        foreach ($attributes['categories'] as $categoryId) {
            CategoryProduct::create([
                'category_id' => $categoryId,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }
    }

    protected function uploadAndLinkPhotosToProduct($product, $request)
    {
        $path = $request->file('primaryPhoto')->store('public/photos');
        $primaryPhoto = Photo::create([
            'url' => str_replace('public', 'http://localhost:8000/storage', $path)
        ]);

        PhotoProduct::create([
            'photo_id' => $primaryPhoto->id,
            'product_id' => $product->id,
            'primary' => true
        ]);

        foreach ($request->file('photos') as $photoFile) {
            $photoPath = $photoFile->store('public/photos');
            $photo = Photo::create([
                'url' => str_replace('public', 'http://localhost:8000/storage', $photoPath)
            ]);

            PhotoProduct::create([
                'photo_id' => $photo->id,
                'product_id' => $product->id,
                'primary' => false
            ]);
        }
    }

    protected function linkPropertiesToProduct($product, $request)
    {
        foreach ($request->input('properties') as $propertyId => $propertyValue) {
            ProductProperty::create([
                'product_id' => $product->id,
                'property_id' => $propertyId,
                'property_value' => json_encode(['value' => $propertyValue])
            ]);
        }
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

    protected function linkSalesChannelsToProduct($product, $attributes)
    {
        foreach ($attributes['salesChannels'] as $salesChannel) {
            ProductSalesChannel::create([
                'product_id' => $product->id,
                'sales_channel_id' => $salesChannel
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
                'communicate_stock' => ['required', 'numeric']
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
                'communicate_stock' => ['nullable', 'numeric']
            ];
        }
    }

    protected function validateCategoryAttributes()
    {
        return [
            'categories' => ['nullable', 'array'],
            'categories.*' => ['required', 'numeric', Rule::exists('categories', 'id')],
            'primaryCategory' => ['required', 'numeric', Rule::exists('categories', 'id')]
        ];
    }

    protected function validateSalesChannelAttributes($request)
    {
        $attributes = $request->validate([
            'salesChannels' => ['array'],
            'salesChannels.*' => ['numeric', Rule::exists('sales_channels', 'id')]
        ]);
        if ($request['salesChannels'] == null) {
            $attributes['salesChannels'] = [];
        }
        return $attributes;
    }

    protected function validatePhotoAttributes(bool $forOnline)
    {
        if ($forOnline) {
            return [
                'primaryPhoto' => ['required', 'image'],
                'photos' => ['nullable', 'array'],
                'photos.*' => ['image']
            ];
        } else {
            return [
                'primaryPhoto' => ['nullable', 'image'],
                'photos' => ['nullable', 'array'],
                'photos.*' => ['image']
            ];
        }
    }

    protected function validatePropertyAttributes()
    {
        return [
            'properties' => ['nullable', 'array'],
            'properties.*' => ['string'] //to do exists
        ];
    }

    protected function validateInventoryAttributes()
    {
        return [
            'location_zones' => ['nullable', 'array'],
            'location_zones.*' => ['numeric'] // to do exists
        ];
    }
}
