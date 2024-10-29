<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function salesChannels()
    {
        return $this->belongsToMany(SalesChannel::class)
            ->using(CategorySalesChannel::class)
            ->withPivot('external_id');
    }

    public function ProductSalesChannels()
    {
        return $this->belongsToMany(ProductSalesChannel::class)
            ->using(CategoryProductSalesChannel::class);
    }

    public function parent_category()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function child_categories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function child_categories_recursive()
    {
        return $this->hasMany(Category::class, 'parent_category_id')->with('child_categories_recursive');
    }

    public function deleteWithChildren()
    {
        // Recursively delete all children
        foreach ($this->child_categories as $child) {
            $child->deleteWithChildren();
        }

        // Delete the category itself
        $this->delete();
    }
}
