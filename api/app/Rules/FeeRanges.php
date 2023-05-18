<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class FeeRanges implements ImplicitRule
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
        if (empty($value)) {
            return true;
        }

        foreach ($value as $feeRange) {
            if (! isset($feeRange->fees) || ! isset($feeRange->feeState)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The fee_ranges structure is not valid.';
    }
}
