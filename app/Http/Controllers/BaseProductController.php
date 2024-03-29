<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Photo;
use App\Models\PhotoProduct;
use App\Models\ProductProperty;
use App\Rules\ValidPropertyKeys;
use App\Models\ProductSalesChannel;
use Illuminate\Validation\Rule;


abstract class BaseProductController extends Controller
{
    protected function validateSalesChannelAttributes($request)
    {
        $attributes = $request->validate([
            'salesChannels' => ['array', 'nullable'],
            'salesChannels.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);
        if ($request['salesChannels'] == null) {
            $attributes['salesChannels'] = [];
        }
        return $attributes;
    }

    protected function validateCategoryAttributes()
    {
        return [
            'categories' => ['nullable', 'array'],
            'categories.*' => ['required', 'numeric', Rule::exists('categories', 'id')],
            'primaryCategory' => ['required', 'numeric', Rule::exists('categories', 'id')]
        ];
    }

    protected function validatePhotoAttributes(bool $forOnline)
    {
        if ($forOnline) {
            return [
                'primaryPhoto' => ['required', 'image'],
                'photos' => ['nullable', 'array'],
                'photos.*' => ['image', 'required']
            ];
        } else {
            return [
                'primaryPhoto' => ['nullable', 'image'],
                'photos' => ['nullable', 'array'],
                'photos.*' => ['image', 'required']
            ];
        }
    }

    protected function validatePropertyAttributes()
    {
        return [
            'properties' => ['nullable', 'array', new ValidPropertyKeys],
            'properties.*' => ['string', 'required']
        ];
    }
    
    protected function linkCategoriesToProduct($product, $attributes)
    {
        // Link primary category
        CategoryProduct::create([
            'category_id' => $attributes['primaryCategory'],
            'product_id' => $product->id,
            'primary' => true
        ]);
    
        // Link all other categories and their parents
        foreach ($attributes['categories'] as $categoryId) {
            $this->linkCategoryAndParentsToProduct($categoryId, $product->id);
        }
    }
    
    protected function linkCategoryAndParentsToProduct($categoryId, $productId)
    {
        // Link the category itself
        CategoryProduct::create([
            'category_id' => $categoryId,
            'product_id' => $productId,
            'primary' => false
        ]);
    
        // Fetch the category
        $category = Category::find($categoryId);
    
        // If the category has a parent, recursively link the parent categories
        if ($category->parent_category_id !== null) {
            $this->linkCategoryAndParentsToProduct($category->parent_category_id, $productId);
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
    
    protected function linkSalesChannelsToProduct($product, $attributes)
    {
        foreach ($attributes['salesChannels'] as $salesChannel) {
            ProductSalesChannel::create([
                'product_id' => $product->id,
                'sales_channel_id' => $salesChannel
            ]);
        }
    }

}