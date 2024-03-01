<?php

namespace App\Rules;

use App\Models\Property;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPropertyKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $propertyIds = Property::pluck('id')->toArray();
        foreach ($value as $key => $data) {
            if (!in_array($key, $propertyIds)) {
                $fail("The key $key does not correspond to a valid property ID.");
            }
        }
    }
}
