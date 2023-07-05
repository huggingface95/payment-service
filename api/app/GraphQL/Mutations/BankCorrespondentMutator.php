<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\BankCorrespondent;
use App\Models\PaymentBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BankCorrespondentMutator extends BaseMutator
{
    use OptimizationCurrencyRegionTrait;


    /**
     * @param $_
     * @param array $args
     * @return Model|BankCorrespondent|Builder
     * @throws GraphqlException
     */
    public function create($_, array $args): \Illuminate\Database\Eloquent\Model|BankCorrespondent|Builder
    {
        /** @var BankCorrespondent $bank */

        $paymentBank = PaymentBank::query()->where('payment_system_id', '=', $args['payment_system_id'])->first();
        if (!$paymentBank){
            throw new GraphqlException('Selected payment_system_id does not have payment bank', 'not found', 404);
        }
        $args['payment_bank_id'] = $paymentBank->id;

        $bank = BankCorrespondent::query()->create($args);

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
     *
     * @throws GraphqlException
     */
    public function update($_, array $args)
    {
        /** @var BankCorrespondent $bank */
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
