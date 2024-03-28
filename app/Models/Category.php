<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function products(){
        return $this->belongsToMany(Product::class)
        ->using(CategoryProduct::class);
    }

    public function parent_category(){
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function child_categories(){
        return $this->hasMany(Category::class,'parent_category_id');
    }
    public function child_categories_recursive()
    {
        return $this->hasMany(Category::class, 'parent_category_id')->with('child_categories_recursive');
    }
    
}