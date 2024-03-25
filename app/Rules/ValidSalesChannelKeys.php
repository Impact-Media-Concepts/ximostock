<?php

namespace App\Rules;

use App\Models\SalesChannel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSalesChannelKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $salesChannelIds = SalesChannel::pluck('id')->toArray();
        foreach ($value as $key => $data) {
            if (!in_array($key, $salesChannelIds)) {
                $fail("The key $key does not correspond to a valid salesChannel ID.");
            }
        }
    }
}
