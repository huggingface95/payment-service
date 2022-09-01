<?php

namespace App\Rules;

use App\Enums\FeeModeEnum;
use Illuminate\Contracts\Validation\ImplicitRule;

class CurrencyRangeMatches implements ImplicitRule
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

        $currencyRanges = $this->getCurrencyRanges($value);

        foreach ($currencyRanges as $currency) {
            return !$this->isRangesIntersection($currency);
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
        return 'The fee_from and fee_to have an intersection range.';
    }

    private function getCurrencyRanges(array $value): array
    {
        $result = [];

        foreach ($value as $val) {
            $fee_modes = $val[0]['fee_modes'];
            $currency_id = $val[0]['currency_id'];

            foreach ($fee_modes as $fee_mode) {
                if ((int) $fee_mode['fee_mode_id'] === FeeModeEnum::RANGE->value) {
                    if (isset($fee_mode['fee_from']) && isset($fee_mode['fee_to'])) {
                        $result[$currency_id][] = [$fee_mode['fee_from'], $fee_mode['fee_to']];
                    }
                }
            }
        }

        return $result;
    }

    private function isRangeOverlap(int $start, int $end, int $secondStart, int $secondEnd): bool
    {
        if ($secondStart < $end && $secondEnd > $start) {
            return true;
        }

        return false;
    }

    private function isRangesIntersection(array $array): bool
    {
        $starts = $ends = $array;

        foreach ($starts as $start) {
            unset($starts[0]);
            $starts = array_values($starts);

            foreach ($ends as $end) {
                if ($start === $end) {
                    continue;
                }

                if ($this->isRangeOverlap($start[0], $start[1], $end[0], $end[1])) {
                    return true;
                }
            }
        }

        return false;
    }

}
