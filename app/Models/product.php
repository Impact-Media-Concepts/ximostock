<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded =['id'];

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

    public function getPrimaryCategoryAttribute():Category
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

    public function getPrimaryPhotoAttribute():Photo
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

    public function getStockAttribute():int
    {
        $inventories = $this->locationZones;

        // Calculate the total stock
        $stock = $inventories->sum(function ($inventory) {
            return $inventory->pivot->stock ?? 0;
        });

        return $stock;
    }

    public function salesChannels()
    {
        return $this->hasMany(ProductSalesChannel::class);
    }

    public function getOnlineAttribute():bool {
        return $this->salesChannels()->exists();
    }

    //calulate the total sales of this product
    public function getSalesAttribute(): int{
        return $this->salesChannels->sum(function ($salesChannel) {
            return $salesChannel->sales->sum('stock');
        });
    }

    public function getConceptAttribute(): bool{

        if($this->parentProduct == null){
            //validate simple product
            if($this->sku == null || $this->title == null || $this->price == null || $this->primaryPhoto == null){
                return true;
            }else{
                return false;
            }
        }else{
            // logic for varation product
        }

        return false;
    }

}
