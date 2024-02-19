<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Ramsey\Uuid\Type\Integer;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sku', 'ean', 'short_description', 'long_description', 'price'];

    public function categories(){
        return $this->belongsToMany(Category::class)
        ->using(CategoryProduct::class)
        ->withPivot('primary');
    }
    
    public function primaryCategory(): Attribute{
        return new Attribute(
            get: function(){
                return $this->belongsToMany(Category::class)
                ->using(CategoryProduct::class)
                ->wherePivot('primary', 1)->first();
            }
        );
    }

    public function photos(){
        return $this->belongsToMany(Photo::class)
        ->using(PhotoProduct::class)
        ->withPivot('primary');
    }

    public function primaryPhoto(): Attribute{
        return new Attribute(
            get: function(){
                return $this->belongsToMany(Photo::class)
                ->using(PhotoProduct::class)
                ->wherePivot('primary', 1)
                ->first();
            }
        );
    }

    public function properties(){
        return $this->belongsToMany(Property::class)
        ->using(ProductProperty::class)
        ->withPivot('property_value');
    }

    public function locationZones(){
        return $this->belongsToMany(LocationZone::class,'inventories')
        ->using(Inventory::class)
        ->withPivot('stock');
    }

    public function stock() : Attribute {
        return new Attribute(
            get: function(){
                $inventories = $this->locationZones()->get();
                $stock = 0;
                foreach($inventories as $inventory){
                    $stock = $stock + $inventory->relations['pivot']->attributes['stock'];
                }
                return $stock;
            }
        );
    }
}
