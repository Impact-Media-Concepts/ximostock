<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;

    public function location_zones(){
        return $this->hasMany(LocationZone::class);
    }
}
