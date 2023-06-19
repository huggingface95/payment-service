<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;

class FeeValueAsObject implements ImplicitRule
{
    public function __construct()
    {
    }

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
            if (!empty($feeRange->fees)) {
                foreach ($feeRange->fees as $item) {
                    if (!empty($item->feeValues)) {
                        foreach ($item->feeValues as $feeValue) {
                            if (empty($feeValue->value)) {
                                return false;
                            }

                            if (!is_object($feeValue->value)) {
                                return true;
                            }

                            if (is_object($feeValue->value) && empty($feeValue->value->standart)) {
                                return false;
                            }
                        }
                    }
                }
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
        return 'The value must be an object.';
    }
}
