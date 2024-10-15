<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductSalesChannel extends  Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll();
    }

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
        return $this->belongsToMany(Property::class)
            ->using(ProductSalesChannelProperty::class);
    }
}
