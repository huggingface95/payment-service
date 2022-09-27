<?php

namespace App\Rules;

use App\Enums\FeeModeEnum;
use Illuminate\Contracts\Validation\ImplicitRule;

class CurrencyMode implements ImplicitRule
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

        foreach ($value as $i => $val) {
            foreach ($val as $fee_mode) {
                if ($fee_mode['mode'] === FeeModeEnum::RANGE->toString()) {
                    if (! isset($fee_mode['amount_from']) && ! isset($fee_mode['amount_to'])) {
                        return false;
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
        return 'The fee_mode_id must contain fee_from and fee_to for range mode.';
    }
}
