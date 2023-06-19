<?php

namespace App\Rules;

use App\Enums\OperationTypeEnum;
use Illuminate\Contracts\Validation\ImplicitRule;

class CurrenciesDestination implements ImplicitRule
{
    public function __construct(private string $operation_type_id)
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
            if (isset($feeRange->fees)) {
                foreach ($feeRange->fees as $item) {
                    if (! empty($item->currencies_destination) && $this->operation_type_id != OperationTypeEnum::EXCHANGE->value) {
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
        return 'The fees must exclude currencies_destination array for the Exchange operation type.';
    }
}
