<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->logOnlyDirty();
    }


    // haal de varianten van een variabel product op
    public function childProducts()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    //haal het hoofdproduct op van een variable product
    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryProduct::class)
            ->withPivot('primary');
    }

    public function getPrimaryCategoryAttribute()
    {
        $primaryCategory = $this->categories->first(function ($category) {
            return $category->pivot->primary == 1;
        });
        return $primaryCategory;
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class)
            ->using(PhotoProduct::class)
            ->withPivot('primary');
    }

    public function getPrimaryPhotoAttribute()
    {
        $primaryPhoto = $this->photos->first(function ($photo) {
            return $photo->pivot->primary == 1;
        });

        return $primaryPhoto;
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class)
            ->using(ProductProperty::class)
            ->withPivot('property_value');
    }

    public function getDecodedPropsAttribute()
    {
        $props = $this->properties;
        $decodedProps = [];
        foreach ($props as $prop) {

            $propjson = json_decode($prop->pivot->property_value);
            array_push($decodedProps, ['name' => $prop->name, 'value' => $propjson->value]);
        }
        return $decodedProps;
    }

    public function locationZones()
    {
        return $this->belongsToMany(LocationZone::class, 'inventories')
            ->using(Inventory::class)
            ->withPivot('stock');
    }

    // Define the accessor to calculate total stock including child products' stock
    public function getStockAttribute(): int
    {
        // Start with the stock of the current product
        $stock = $this->calculateStock();

        // If there are child products, add their stock
        if (Count($this->childProducts) > 0) {
            $childStock = $this->childProducts->sum(function ($childProduct) {
                return $childProduct->stock;
            });
            $stock += $childStock;
        }

        return $stock;
    }

    protected function calculateStock(): int
    {
        $inventories = $this->locationZones;

        // Calculate the total stock of the current product
        $stock = $inventories->sum(function ($inventory) {
            return $inventory->pivot->stock ?? 0;
        });

        return $stock;
    }

    public function salesChannels()
    {
        return $this->belongsToMany(SalesChannel::class, 'product_sales_channel')
            ->using(ProductSalesChannel::class);
    }

    //is for online
    public function productSalesChannels()
    {
        return $this->hasMany(ProductSalesChannel::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    //calulate the total sales of this product
    public function getTotalSalesAttribute(): int
    {
        $totalSales = 0;

        // Loop through each sale related to the product
        foreach ($this->sales as $sale) {
            // Add the stock of each sale to the total sales count
            $totalSales += $sale->stock;
        }

        return $totalSales;
    }


    //returns true if the product is a concept and cant be set to online 
    public function getConceptAttribute(): bool
    {
        if ($this->childProducts->isEmpty()) {
            //validate simple product
            if ($this->sku == null || $this->title == null || $this->price == null || $this->primaryPhoto == null || $this->primaryCategory == null) {
                return true;
            } else {
                return false;
            }
        } else {
            //validate variant product
            if ($this->primaryPhoto == null || $this->title == null || $this->price == null || $this->primaryCategory == null) {
                return true;
            } else {
                //validate children of variant product
                foreach ($this->childProducts as $child) {
                    if ($child->sku == null) {
                        return true;
                    }
                }
                return false;
            }
        }
        return false;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
            )
        );

        $query->when(
            $filters['categories'] ?? false,
            fn ($query, $categories) =>
            $query->whereHas('categories', function ($query) use ($categories) {
                // Group by product id and count the number of distinct category IDs
                $query->select('product_id')
                    ->whereIn('category_id', $categories)
                    ->groupBy('product_id')
                    ->havingRaw('COUNT(DISTINCT category_id) = ?', [count($categories)]);
            })
        );

        $query->when(
            $filters['properties'] ?? false,
            function ($query, $properties) {
                $query->whereHas('properties', function ($query) use ($properties) {
                    $query->where(function ($query) use ($properties) {
                        foreach ($properties as $propertyId => $propertyValue) {
                            $query->orWhere(function ($query) use ($propertyId, $propertyValue) {
                                $query->where('property_id', $propertyId);
                                // Apply the condition based on the property value
                                if ($propertyValue !== null) {
                                    $propertyType = Property::find($propertyId)->type ?? null;

                                    // Cast the property value based on its type
                                    switch ($propertyType) {
                                        case 'bool':
                                            $query->whereJsonContains('property_value', ['value' => (bool)$propertyValue]);
                                            break;
                                        case 'number':
                                            $query->whereJsonContains('property_value', ['value' => (int)$propertyValue]);
                                            break;
                                        case 'text':
                                            $query->whereJsonContains('property_value', ['value' => (string)$propertyValue]);
                                            break;
                                        case 'singleselect':
                                            $query->whereJsonContains('property_value', ['value' => (string)$propertyValue]);
                                            break;
                                        case 'multiselect':
                                            $propertyValue = explode(',', $propertyValue);
                                            sort($propertyValue);
                                            $query->whereJsonContains('property_value', ['value' => $propertyValue]);
                                            break;
                                    }
                                }
                            });
                        }
                    });
                    // Group by product id and count the number of distinct property IDs
                    $query->select('product_id')
                        ->groupBy('product_id')
                        ->havingRaw('COUNT(DISTINCT property_id) = ?', [count($properties)]);
                });
            }
        );


        // Apply different orderings based on the provided input
        if (!isset($filters['orderByInput'])) {
            $filters['orderByInput'] = null;
        }
        match ($filters['orderByInput']) {
            'NameAscending' => $query->orderBy('title'),
            'NameDescending' => $query->orderByDesc('title'),
            'PriceAscending' => $query->orderBy('price'),
            'PriceDescending' => $query->orderByDesc('price'),
            'SKUAscending' => $query->orderBy('sku'),
            'SKUDescending' => $query->orderByDesc('sku'),
            'StockDescending' => $query->orderByDesc('orderByStock'),
            'StockAscending' => $query->orderBy('orderByStock'),
            'UpdatedAtAscending' => $query->orderBy('updated_at'),
            'UpdatedAtDescending' => $query->orderByDesc('updated_at'),
            'SoldDescending' => $query->orderByDesc('orderBySold'),
            'SoldAscending' => $query->orderBy('orderBySold'),
            'StatusDescending' => $query->orderByDesc('orderByOnline'),
            'StatusAscending' => $query->orderBy('orderByOnline'),
            default => $query->orderByDesc('updated_at')
        };
    }

    public function scopeWorkspace($query, $workspace)
    {
        if ($workspace) {
            $query->where('work_space_id', '=', $workspace);
        }
    }
}

