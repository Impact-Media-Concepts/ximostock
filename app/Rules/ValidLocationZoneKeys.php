<?php

namespace App\Rules;

use App\Models\LocationZone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLocationZoneKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $locationZoneIds = LocationZone::pluck('id')->toArray();
        foreach ($value as $key => $data) {
            if (!in_array($key, $locationZoneIds)) {
                $fail("The key $key does not correspond to a valid location zone.");
            }
        }
    }
}
