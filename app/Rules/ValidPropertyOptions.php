<?php

namespace App\Rules;

use App\Models\Property;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPropertyOptions implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $key => $data) {
            $property = Property::where('id', $key)->get()->first();
            if($property->type === 'multiselect' || $property->type === 'singleselect'){
                $options = explode(',',$data);
                foreach($options as $option){
                    if(!in_array($option,$property->options)){
                        $fail("$option is not a vallid option for property $key");
                    }
                }
            }
        }
    }
}