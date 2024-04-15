<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesChannel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sales_channel')
        ->using(ProductSalesChannel::class)
        ->withPivot(['title','price', 'short_description', 'long_description', 'discount']);
    }
}
