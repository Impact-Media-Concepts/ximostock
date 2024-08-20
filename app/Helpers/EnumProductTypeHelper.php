<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class EnumProductTypeHelper
{
    /**
     * Retrieve enum values from a model's casts property.
     *
     * @param string $modelClass
     * @param string $property
     * @return array
     */
    public static function getEnumValuesFromProduct(string $modelClass, string $property): array
    {
        $model = new $modelClass;
        $casts = $model->getCasts();

        if (isset($casts[$property])) {
            $type = $casts[$property];
            if (is_subclass_of($type, 'BenSampo\Enum\Enum')) {
                $enumValues = $type::getValues();
                Log::info($enumValues);
                return $enumValues;
            }
        }

        return [];
    }
}
