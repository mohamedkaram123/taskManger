<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = substr($value, strpos($value, ',') + 1);
        if (!base64_encode(base64_decode($value, true)) === $value) {
            $fail('The :attribute must be base64.');
        }
    }
}
