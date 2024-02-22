<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalesChannel extends Model
{
    protected $guarded = [];

    protected $table = 'product_sales_channel';

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
