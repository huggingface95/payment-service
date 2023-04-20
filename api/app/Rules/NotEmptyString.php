<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotEmptyString implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value !== null && trim($value) !== '';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute field must not be an empty string.";
    }
}
