<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    // haal de varianten van een variabel product op
    public function childProducts()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    //haal het hoofdproduct op van een variable product
    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryProduct::class)
            ->withPivot('primary');
    }

    public function getPrimaryCategoryAttribute(): Category
    {
        $primaryCategory = $this->categories->first(function ($category) {
            return $category->pivot->primary == 1;
        });
        return $primaryCategory;
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class)
            ->using(PhotoProduct::class)
            ->withPivot('primary');
    }

    public function getPrimaryPhotoAttribute(): Photo
    {
        $primaryPhoto = $this->photos->first(function ($photo) {
            return $photo->pivot->primary == 1;
        });

        return $primaryPhoto;
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class)
            ->using(ProductProperty::class)
            ->withPivot('property_value');
    }

    public function getDecodedPropsAttribute()
    {
        $props = $this->properties;
        $decodedProps = [];
        foreach ($props as $prop) {

            $propjson = json_decode($prop->pivot->property_value);
            array_push($decodedProps, ['name' => $prop->name, 'value' => $propjson->value]);
        }
        return $decodedProps;
    }

    public function locationZones()
    {
        return $this->belongsToMany(LocationZone::class, 'inventories')
            ->using(Inventory::class)
            ->withPivot('stock');
    }

    // Define the accessor to calculate total stock including child products' stock
    public function getStockAttribute(): int
    {
        // Start with the stock of the current product
        $stock = $this->calculateStock();

        // If there are child products, add their stock
        if ($this->childProducts()->exists()) {
            $childStock = $this->childProducts->sum(function ($childProduct) {
                return $childProduct->stock;
            });
            $stock += $childStock;
        }

        return $stock;
    }
    protected function calculateStock(): int
    {
        $inventories = $this->locationZones;

        // Calculate the total stock of the current product
        $stock = $inventories->sum(function ($inventory) {
            return $inventory->pivot->stock ?? 0;
        });

        return $stock;
    }

    public function salesChannels()
    {
        return $this->hasMany(ProductSalesChannel::class);
    }


    //calulate the total sales of this product
    public function getSalesAttribute(): int
    {
        $totalStock = 0;
        foreach ($this->salesChannels as $salesChannel) {
            $totalStock += $salesChannel->sales->sum('stock');
        }
        return $totalStock;
    }

    //returns true if the product is a concept and cant be set to online 
    public function getConceptAttribute(): bool
    {
        if ($this->childProducts->isEmpty()) {
            //validate simple product
            if ($this->sku == null || $this->title == null || $this->price == null || $this->primaryPhoto == null || $this->primaryCategory == null) {
                return true;
            } else {
                return false;
            }
        } else {
            //validate variant product
            if ($this->primaryPhoto == null || $this->title == null || $this->price == null || $this->primaryCategory == null) {
                return true;
            } else {
                //validate children of variant product
                foreach ($this->childProducts as $child) {
                    if ($child->sku == null) {
                        return true;
                    }
                }
                return false;
            }
        }
        return false;
    }
}
