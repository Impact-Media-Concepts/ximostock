<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VallidCategoryKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $categoryIds = Category::pluck('id')->toArray();
        foreach ($value as $key => $data) {
            if (!in_array($key, $categoryIds)) {
                $fail("The key $key does not correspond to a valid Category.");
            }
        }
    }
}
