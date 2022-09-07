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
            $fee_modes = $val[0]['fee_modes'];

            foreach ($fee_modes as $fee_mode) {
                if ((int) $fee_mode['fee_mode_id'] === FeeModeEnum::RANGE->value) {
                    if (! isset($fee_mode['fee_from']) && ! isset($fee_mode['fee_to'])) {
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
