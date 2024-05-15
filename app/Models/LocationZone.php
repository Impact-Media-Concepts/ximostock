<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationZone extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function inventory_location(){
        return $this->belongsTo(InventoryLocation::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }
}
