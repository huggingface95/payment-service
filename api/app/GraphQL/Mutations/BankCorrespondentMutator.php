<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\BankCorrespondent;

class BankCorrespondentMutator extends BaseMutator
{
    use OptimizationCurrencyRegionTrait;

    /**
     * @param    $_
     * @param array $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        $bank = BankCorrespondent::create($args);

        if (isset($args['currencies_and_regions'])) {
            $requestCurrenciesRegions = $this->optimizeCurrencyRegionInput($args['currencies_and_regions']);

            foreach ($requestCurrenciesRegions->where('region_id', '>', 0) as $currenciesRegion) {
                $bank->currencies()->attach($currenciesRegion['currency_id'], ['region_id' => $currenciesRegion['region_id']]);
            }
        }

        return $bank;
    }

    /**
     * @param    $_
     * @param array $args
     * @return mixed
     * @throws GraphqlException
     */
    public function update($_, array $args)
    {
        $bank = BankCorrespondent::find($args['id']);
        if (!$bank) {
            throw new GraphqlException('Not found', 'not found', 404);
        }
        $bank->update($args);

        if (isset($args['currencies_and_regions'])) {
            $requestCurrenciesRegions = $this->optimizeCurrencyRegionInput($args['currencies_and_regions']);

            $bank->currencies()->detach($requestCurrenciesRegions->pluck('currency_id')->unique());
            foreach ($requestCurrenciesRegions->where('region_id', '>', 0) as $currenciesRegion) {
                $bank->currencies()->attach($currenciesRegion['currency_id'], ['region_id' => $currenciesRegion['region_id']]);
            }
        }

        return $bank;
    }
}
