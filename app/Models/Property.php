<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Enums\PropertyType;
use InvalidArgumentException;

class Property extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'values' => 'array',
        'type' => PropertyType::class,
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(ProductProperty::class)
            ->withPivot('property_value');
    }

    // Accessor for 'options' from 'values'
    public function getOptionsAttribute()
    {
        $values = $this->values ?? [];
        return $values['options'] ?? [];
    }

    // Helper method to get allowed types (now using Enum)
    public function getAllowedTypes()
    {
        return PropertyType::getValues();
    }
}
