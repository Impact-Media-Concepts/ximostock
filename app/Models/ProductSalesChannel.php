<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalesChannel extends  Pivot
{

    protected $guarded = ['id'];

    protected $table = 'product_sales_channel';

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryProductSalesChannel::class)
            ->withPivot('primary');
    }

    public function properties()
    {
        return $this->belongsToMany(Category::class)
            ->using(ProductSalesChannelProperty::class)
            ->withPivot('property_value');
    }
}
