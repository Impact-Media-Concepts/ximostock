<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationZone extends Model
{
    use HasFactory;

    public function inventory_location(){
        return $this->belongsTo(InventoryLocation::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class);
    }
}
