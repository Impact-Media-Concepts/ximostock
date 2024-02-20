<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sku', 'ean', 'parent_product_id', 'short_description', 'long_description', 'price'];

    // haal de varianten van een variabel product op
    public function childProducts(){
        return $this->hasMany(Product::class,'parent_product_id');
    }
 
    //haal het hoofdproduct op van een variable product
    public function parentProduct(){
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class)
        ->using(CategoryProduct::class)
        ->withPivot('primary');
    }

    public function getPrimaryCategoryAttribute()
    {
        $primaryCategory = $this->categories->first(function ($category) {
            return $category->pivot->primary == 1;
        });

        return $primaryCategory;
    }

    public function photos(){
        return $this->belongsToMany(Photo::class)
        ->using(PhotoProduct::class)
        ->withPivot('primary');
    }

    public function getPrimaryPhotoAttribute()
    {
        $primaryPhoto = $this->photos->first(function ($photo) {
            return $photo->pivot->primary == 1;
        });

        return $primaryPhoto;
    }

    public function properties(){
        return $this->belongsToMany(Property::class)
        ->using(ProductProperty::class)
        ->withPivot('property_value');
    }

    public function locationZones()
    {
        return $this->belongsToMany(LocationZone::class,'inventories')
        ->using(Inventory::class)
        ->withPivot('stock');
    }

    public function getStockAttribute()
    {
        $inventories = $this->locationZones;

        // Calculate the total stock
        $stock = $inventories->sum(function ($inventory) {
            return $inventory->pivot->stock ?? 0;
        });

        return $stock;
    }
}
