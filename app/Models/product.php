<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'sku', 'ean', 'short_description', 'long_description', 'price'];

    public function categories(){
        return $this->belongsToMany(Category::class)
        ->using(CategoryProduct::class)
        ->withPivot('primary');
    }

    public function primaryCategory(){
        return $this->belongsToMany(Category::class)
        ->using(CategoryProduct::class)
        ->wherePivot('primary', 1);
    }

    public function photos(){
        return $this->belongsToMany(Photo::class)
        ->using(PhotoProduct::class)
        ->withPivot('primary');
    }

    public function primaryPhoto(){
        return $this->belongsToMany(Photo::class)
        ->using(PhotoProduct::class)
        ->wherePivot('primary', 1);
    }

    public function inventory(){
        return $this->belongsToMany(LocationZone::class,'inventories')
        ->using(Inventory::class)
        ->withPivot('stock');
    }
}
