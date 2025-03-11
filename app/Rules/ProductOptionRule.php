<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductOptionRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $options = $value;
        foreach ($options as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                $fail("invalid product option");
                return;
            }
        }
    }
}
