<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\BankCorrespondent;
use App\Models\PaymentBank;
use Illuminate\Support\Facades\DB;

class PaymentBankMutator extends BaseMutator
{
    use OptimizationCurrencyRegionTrait;

    /**
     * @param    $_
     * @param array $args
     * @return mixed
     * @throws GraphqlException
     */
    public function create($_, array $args)
    {
        try {
            DB::beginTransaction();

            /** @var PaymentBank $bank */
            $bank = PaymentBank::query()->create($args);


            if (isset($args['currencies_and_regions'])) {
                $requestCurrenciesRegions = $this->optimizeCurrencyRegionInput($args['currencies_and_regions']);

                foreach ($requestCurrenciesRegions->where('region_id', '>', 0) as $currenciesRegion) {
                    $bank->currencies()->attach($currenciesRegion['currency_id'], ['region_id' => $currenciesRegion['region_id']]);
                }
            }

            if (isset($args['bank_correspondent_id'])) {
                $correspondents = BankCorrespondent::query()->whereIn('id', $args['bank_correspondent_id'])->get();
                $bank->bankCorrespondents()->saveMany($correspondents);
            }

            DB::commit();

            return $bank;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param    $_
     * @param array $args
     * @return mixed
     * @throws GraphqlException
     */
    public function update($_, array $args)
    {
        try {
            DB::beginTransaction();

            /** @var PaymentBank $bank */
            $bank = PaymentBank::query()->find($args['id']);
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

            if (isset($args['bank_correspondent_id'])) {
                $bank->bankCorrespondents()->whereNotIn('id', $args['bank_correspondent_id'])->update(['payment_bank_id' => null]);
                BankCorrespondent::query()->whereIn('id', $args['bank_correspondent_id'])->update(['payment_bank_id' => $bank->id]);
            }
            DB::commit();

            return $bank;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }
}
