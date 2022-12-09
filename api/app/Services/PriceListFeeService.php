<?php

namespace App\Services;

use App\Enums\FeeModeEnum;

class PriceListFeeService extends AbstractService
{

    public function convertFeeRangesToFees(array $feeRanges): array
    {
        $fees = [];

        // Range
        $r = 0;
        foreach ($feeRanges as $feeRange) {
            if ($feeRange->feeState == 'Range') {
                foreach ($feeRange->fees as $f => $fee) {
                    foreach ($fee->currencies as $c => $currency) {
                        $fees[$currency]['currency_id'] = (string) $currency;
                        $fees[$currency]['fee'][$r][] = [
                            'mode' => FeeModeEnum::RANGE->toString(),
                            'amount_from' => $feeRange->range->from,
                            'amount_to' => $feeRange->range->to,
                        ];

                        foreach ($fee->feeValues as $feeValue) {
                            if ($feeValue->mode == '%') {
                                $fees[$currency]['fee'][$r][] = [
                                    'mode' => FeeModeEnum::PERCENT->toString(),
                                    'percent' => $feeValue->value,
                                ];
                            } else if ($feeValue->mode == 'fix') {
                                $fees[$currency]['fee'][$r][] = [
                                    'mode' => FeeModeEnum::FIX->toString(),
                                    'fee' => $feeValue->value,
                                ];
                            }
                        }

                        $r++;
                    }
                }
            }
        }

        // All ranges
        foreach ($feeRanges as $feeRange) {
            if ($feeRange->feeState == 'Multi') {
                foreach ($feeRange->fees as $i => $fee) {
                    foreach ($fee->currencies as $k => $currency) {
                        $fees[$currency]['currency_id'] = (string) $currency;

                        foreach ($fee->feeValues as $feeValue) {
                            if ($feeValue->mode == '%') {
                                $fees[$currency]['fee'][$r][] = [
                                    'mode' => FeeModeEnum::PERCENT->toString(),
                                    'percent' => $feeValue->value,
                                ];
                            } else if ($feeValue->mode == 'fix') {
                                $fees[$currency]['fee'][$r][] = [
                                    'mode' => FeeModeEnum::FIX->toString(),
                                    'fee' => $feeValue->value,
                                ];
                            }
                        }

                        $r++;
                    }
                }
            }
        }

        return $fees;
    }

    public function convertFeesToFeeRanges($fees): array
    {
        $feeRanges = array_merge(
            $this->makeFeeRangesMultiArray($fees),
            $this->makeFeeRangesArray($fees)
        );

        return array_values($feeRanges);
    }

    public function makeFeeRangesMultiArray($fees): array
    {
        $multi = [];
        $multiFees = [];

        foreach ($fees as $feeItems) {
            $key = $this->getKeyName($feeItems['fee']);
            $feeItems = $feeItems->toArray();

            if (array_search(FeeModeEnum::RANGE->toString(), array_column($feeItems['fee'], 'mode')) === false) {
                $multiFees[$key]['fees']['currencies'][] = $feeItems['currency_id'];

                if (!in_array($feeItems['fee'], $multiFees[$key]['fees']['feeValues'] ?? [], true)) {
                    $multiFees[$key]['fees']['feeValues'] = $feeItems['fee'];
                }
            }
        }

        $multi[] = ['feeState' => 'Multi'];
        foreach ($multiFees as $multiFee) {
            $newMultiFees['currencies'] = $multiFee['fees']['currencies'];
            $newMultiFees['feeValues'] = [];
            foreach ($multiFee['fees']['feeValues'] as $fee) {
                if ($fee['mode'] == FeeModeEnum::PERCENT->toString()) {
                    $newMultiFees['feeValues'][] = [
                        'mode' => '%',
                        'value' => $fee['percent'],
                    ];
                } else if ($fee['mode'] == FeeModeEnum::FIX->toString()) {
                    $newMultiFees['feeValues'][] = [
                        'mode' => 'fix',
                        'value' => $fee['fee'],
                    ];
                }
            }

            $multi[0]['fees'][] = $newMultiFees;
        }

        return isset($multi[0]['fees']) ? $multi : [];
    }

    public function makeFeeRangesArray($fees): array
    {
        $ranges = [];
        $feeRanges = [];

        foreach ($fees as $feeItems) {
            $key = $this->getKeyName($feeItems['fee']);
            $feeItems = $feeItems->toArray();

            if (array_search(FeeModeEnum::RANGE->toString(), array_column($feeItems['fee'], 'mode')) !== false) {
                $feeRanges[$key]['feeState'] = 'Range';
                $feeRanges[$key]['range'] = [
                    'from' => $feeItems['fee'][0]['amount_from'],
                    'to' => $feeItems['fee'][0]['amount_to'],
                ];
                $feeRanges[$key]['fees']['currencies'][] = $feeItems['currency_id'];

                if (!in_array($feeItems['fee'], $feeRanges[$key]['fees']['feeValues'] ?? [], true)) {
                    $feeRanges[$key]['fees']['feeValues'][] = $feeItems['fee'];
                }
            }
        }

        // correct fee values
        $f = [];
        foreach ($feeRanges as $feeRange) {
            foreach ($feeRange['fees']['feeValues'] as $fees) {
                $newFeeValues = [];
                foreach ($fees as $fee) {
                    if ($fee['mode'] == FeeModeEnum::PERCENT->toString()) {
                        $newFeeValues[] = [
                            'mode' => '%',
                            'value' => $fee['percent'],
                        ];
                    } else if ($fee['mode'] == FeeModeEnum::FIX->toString()) {
                        $newFeeValues[] = [
                            'mode' => 'fix',
                            'value' => $fee['fee'],
                        ];
                    }
                }

                $feeRange['fees']['feeValues'] = $newFeeValues;
            }

            $f[] = $feeRange;
        }

        // group fees by range value
        foreach ($f as $feeRange) {
            if ($feeRange['feeState'] == 'Range') {
                $k = $feeRange['range']['from'] . '_' . $feeRange['range']['to'];

                $ranges[$k]['feeState'] = 'Range';
                $ranges[$k]['range'] = [
                    'from' => $feeRange['range']['from'],
                    'to' => $feeRange['range']['to'],
                ];
                $ranges[$k]['fees'][] = $feeRange['fees'];
            }
        }

        return $ranges;
    }

    public function getKeyName(string $string)
    {
        return str_replace(['{', '}', ':', '"', ' ', ',', ']', '['], '_', $string);
    }

}
