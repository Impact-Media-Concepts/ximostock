<?php

namespace App\Rules;

use App\Models\WorkSpace;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidWorkspaceKeys implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $workspaceIds = WorkSpace::pluck('id')->toArray();
        foreach ($value as $key => $data) {
            if (!in_array($key, $workspaceIds)) {
                $fail("The key $key does not correspond to a valid Workspace ID.");
            }
        }
    }
}
