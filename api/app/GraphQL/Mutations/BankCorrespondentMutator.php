<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\BankCorrespondent;
use Illuminate\Database\Eloquent\Builder;

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

            $bank->currencies()->detach();
            foreach ($requestCurrenciesRegions->where('region_id', '>', 0) as $currenciesRegion) {
                $bank->currencies()->attach($currenciesRegion['currency_id'], ['region_id' => $currenciesRegion['region_id']]);
            }
        }

        return $bank;
    }

    public function deleteCurrenciesAndRegions($_, array $args): BankCorrespondent
    {
        /** @var BankCorrespondent $bank */
        $bank = BankCorrespondent::query()->findOrFail($args['id']);

        $bank->currenciesRegions()
            ->where(function (Builder $q) use ($args) {
                foreach ($args['currencies_and_regions'] as $currencyRegion) {
                    $q->orWhere(function (Builder $q) use ($currencyRegion) {
                        $q->whereIn('currency_id', $currencyRegion['currency_id']);
                        if (!empty($currencyRegion['regions'])) {
                            $q->whereIn('region_id', $currencyRegion['regions']);
                        }
                    });
                }
            })->delete();

        return $bank;
    }
}
