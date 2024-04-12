<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalesChannel extends Model
{
    protected $guarded = ['id'];

    protected $table = 'product_sales_channel';

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryProductSalesChannels::class)
            ->withPivot('primary');
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
