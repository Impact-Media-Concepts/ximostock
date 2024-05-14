<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryLocation;
use App\Models\SalesChannel;
use App\Models\Property;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ProductProperty;
use App\Models\WorkSpace;
use App\Rules\ValidLocationZoneKeys;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductVariationController extends BaseProductController
{
    public function create(Request $request)
    {
        $perPage = $request->input('perPage', 20);
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $products = Product::where('work_space_id', $request['workspace']);
            $categories = Category::where('work_space_id', $request['workspace']);
            $properties = Property::where('work_space_id', $request['workspace']);
            $salesChannels = SalesChannel::where('work_space_id', $request['workspace']);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        }else{
            $products = Product::where('work_space_id', Auth::user()->work_space_id);
            $categories = Category::where('work_space_id', Auth::user()->work_space_id);
            $properties = Property::where('work_space_id', Auth::user()->work_space_id);
            $salesChannels = SalesChannel::where('work_space_id', Auth::user()->work_space_id);
            $workspaces = null;
            $activeWorkspace = null;
        }
        
        $properties = Property::all();
        $salesChannels = $salesChannels->get();

        return view('product.createVariant', [
            'categories' => $categories->with(['child_categories'])->whereNull('parent_category_id')->get(),
            'properties' => $properties,
            'locations' => InventoryLocation::with(['location_zones'])->get(),
            'salesChannels' => SalesChannel::all(),
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
            'sidenavActive' => 'products',
            'products' => $products,
            'perPage' => $perPage,
            'search' => $request['search'],
            'selectedCategories' => $request['categories'],
            'orderBy' => $request['orderByInput'],
            'discountErrors' => $request->session()->get('discountErrors'),
            'selectedProperties' => $request['properties']
        ]);
    }

    public function store()
    {
        $request = request();
        // Validate the incoming request data
        $saleschannelAttributes = $this->validateSalesChannelAttributes($request);
        $forOnline = false;
        if(count($saleschannelAttributes) >0){
            $forOnline = true;
        }
        $validationRules = [];
        $validationRules += $this->validateMainProductAttributes($forOnline);
        $validationRules += $this->validateCategoryAttributes();
        $validationRules += $this->validatePhotoAttributes($forOnline);
        $validationRules += $this->validatePropertyAttributes();
        $validationRules += $this->validateVariantAttributes($forOnline);
        $attributes = $request->validate($validationRules);
        //create main product
        $mainProduct = $this->createMainProduct($attributes);
        $this->linkCategoriesToProduct($mainProduct, $attributes);
        $this->uploadAndLinkPhotosToProduct($mainProduct, $request);
        $this->linkPropertiesToProduct($mainProduct, $request);
        if ($saleschannelAttributes['salesChannels'] != null) {
            $this->linkSalesChannelsToProduct($mainProduct, $saleschannelAttributes);
        }
        //ceate product variants
        $this->createVariantProducts($mainProduct, $attributes['variants']);

        return redirect('/products');
    }

    protected function validateMainProductAttributes(bool $forOnline):array
    {
        if($forOnline){
            return [
                'title' => ['required', 'string'],
                'short_description' => ['nullable', 'string'],
                'long_description' => ['nullable', 'string'],
                'price' => ['required', 'numeric'],
                'backorders'=>['boolean'],
                'communicate_stock'=>['boolean']
            ];
        }
        else{
            return [
                'title' => ['nullable', 'string'],
                'short_description' => ['nullable', 'string'],
                'long_description' => ['nullable', 'string'],
                'price' => ['nullable', 'numeric'],
                'backorders'=>['boolean'],
                'communicate_stock'=>['boolean']
            ];
        }
    }

    protected function validateVariantAttributes(bool $forOnline) :array
    {
        if($forOnline){
            return [
                'variants' => ['required','array'],
                'variants.*' => ['required','array'],
                'variants.*.property_id' => ['required', 'array'],
                'variants.*.property_id.*' => ['required', 'numeric', Rule::exists('properties', 'id')],
                'variants.*.property_value' => ['required', 'array'],
                'variants.*.property_value.*' => ['required','string'],
                'variants.*.sku' => ['required', 'string', 'unique:products,sku'],
                'variants.*.ean' => ['digits:13', 'nullable', 'unique:products,ean'],
                'variants.*.price' => ['nullable'],
                'variants.*.location_zones' => ['array','nullable', new ValidLocationZoneKeys],
                'variants.*.location_zones.*' => ['numeric', 'required']
            ];
        }else{
            return [
                'variants' => ['required','array'],
                'variants.*' => ['required','array'],
                'variants.*.property_id' => ['required', 'array'],
                'variants.*.property_id.*' => ['required', 'numeric', Rule::exists('properties', 'id')],
                'variants.*.property_value' => ['required', 'array'],
                'variants.*.property_value.*' => ['required','string'],
                'variants.*.sku' => ['nullable', 'string', 'unique:products,sku'],
                'variants.*.ean' => ['digits:13', 'nullable', 'unique:products,ean'],
                'variants.*.price' => ['nullable'],
                'variants.*.location_zones' => ['array', 'nullable', new ValidLocationZoneKeys],
                'variants.*.location_zones.*' => ['numeric','required']
            ];
        }
    }

    protected function createMainProduct($attributes){
        return Product::create([
            'title' => $attributes['title'],
            'price' => $attributes['price'],
            'long_description' => $attributes['long_description'],
            'short_description' => $attributes['short_description'],
            'backorders' => $attributes['backorders'],
            'communicate_stock' => $attributes['communicate_stock']
        ]);
    }

    protected function createVariantProducts($product, $variants){
        foreach($variants as $variant){
            //create variant product
            $attributes = [
            'parent_product_id' => $product->id,
            'sku' => $variant['sku'],
            'ean' => $variant['ean']
            ];
            if($variant['price'] != null && $variant['price']  != 0.00){
                array_push($attributes, ['price' => $variant['price']] );
            }
            $vProduct = Product::create(
                $attributes
            );

            //add variant properties
            for($x = 1; $x <= sizeof($variant['property_id']); $x++){
                ProductProperty::create([
                    'product_id' => $vProduct->id,
                    'property_id' => $variant['property_id'][$x],
                    'property_value' => json_encode(['value' => $variant['property_value'][$x]]) 
                ]);
            }

            //add stock
            foreach($variant['location_zones'] as $zone => $stock){
                Inventory::create([
                    'product_id' =>  $vProduct->id,
                    'location_zone_id'=> $zone,
                    'stock' => $stock
                ]);
            }
        }
    }
}