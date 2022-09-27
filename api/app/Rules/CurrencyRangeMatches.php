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
            return ! $this->isRangesIntersection($currency);
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
        return 'The amount_from and amount_to have an intersection range.';
    }

    private function getCurrencyRanges(array $value): array
    {
        $result = [];
        $currency_id = $value['currency_id'];

        foreach ($value['fee'] as $feeModes) {
            foreach ($feeModes as $feeMode) {
                if ($feeMode['mode'] === FeeModeEnum::RANGE->toString()) {
                    if (isset($feeMode['amount_from']) && isset($feeMode['amount_to'])) {
                        $result[$currency_id][] = [$feeMode['amount_from'], $feeMode['amount_to']];
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
