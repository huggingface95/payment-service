<?php

namespace App\GraphQL\ArgumentFilters;

use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\BankCorrespondent;
use App\Models\PaymentBank;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Context;

class PaymentBankArgumentFilter extends BaseArgumentFilter
{
    use OptimizationCurrencyRegionTrait;

    public static array $filters = [
        'hasCurrenciesRegionsFilterByCurrencyId' => 'currency_id',
        'hasCurrenciesRegionsFilterByRegionId' => 'region_id',
        'HAS_CURRENCIES_REGIONS_FILTER_BY_CURRENCY_ID' => 'currency_id',
        'HAS_CURRENCIES_REGIONS_FILTER_BY_REGION_ID' => 'region_id',
    ];

    public function filter(PaymentBank|BankCorrespondent $paymentBank, array $args, Context $c, ResolveInfo $resolveInfo): array
    {
        $result = $this->filterVariables($resolveInfo->variableValues);
        if (!count($result)) {
            $result = $this->filterQuery($resolveInfo->operation->selectionSet->toArray(true));
        }

        $query = $paymentBank->currenciesRegions();

        foreach ($result as $operator => $conditions) {
            foreach ($conditions as $condition) {
                if ($operator == 'OR') {
                    $query->orWhere(...$condition);
                } else {
                    $query->where(...$condition);
                }
            }
        }

        $currenciesRegions = $query->with('currency', 'region')->get()->groupBy('currency_id')->map(function ($records) {
            return ['regions' => $records->pluck('region'), 'currency' => $records->pluck('currency')->first()];
        });

        return $this->optimizeCurrencyRegionResponse($currenciesRegions);
    }

}
