<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesChannel extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty();
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sales_channel')
        ->using(ProductSalesChannel::class)
        ->withPivot(['title','price', 'short_description', 'long_description', 'discount']);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_sales_channel')
        ->using(CategorySalesChannel::class)
        ->withPivot(['external_id']);
    }

    public function Properties(){
        return $this->belongsToMany(Property::class, 'property_sales_channel')
        ->using(PropertySalesChannel::class)
        ->withPivot(['external_id']);
    }
}
