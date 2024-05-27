<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function location_zones(){
        return $this->hasMany(LocationZone::class);
    }
}
