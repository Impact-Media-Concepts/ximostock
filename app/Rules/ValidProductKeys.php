<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidProductKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productIds = Product::pluck('id')->toArray(); // Assuming Product is your model
        foreach ($value as $key => $data) {
            if (!in_array($key, $productIds)) {
                $fail("The key $key does not correspond to a valid product ID.");
            }
        }
    }
}
