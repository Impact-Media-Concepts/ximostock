<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Enums\PropertyType;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

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
            ->logAll();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->using(ProductProperty::class)
            ->withPivot('property_value');
    }

    // Accessor for 'options' from 'values'
    public function getOptionsDecodedAttribute()
    {
        $options = json_decode($this->options);
        $options = $options->options;
        Log::debug('debug options decoded');
        Log::debug($options);
        return $options ?? [];
    }

    public function getSaleschannelsAttribute()
    {
        $propertyLinks = PropertySalesChannel::where('property_id', $this->id)->get();
        $salesChannels = $propertyLinks->map(function ($propertyLink) {
            return $propertyLink->salesChannel;
        })->unique();
        return $salesChannels->values();
    }

    // Helper method to get allowed types (now using Enum)
    public function getAllowedTypes()
    {
        return PropertyType::getValues();
    }
}
