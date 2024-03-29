<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    public function products(){
        return $this->belongsToMany(Product::class)
        ->using(PhotoProduct::class);
    }
}
