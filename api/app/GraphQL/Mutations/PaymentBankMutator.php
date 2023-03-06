<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PaymentBank;

class PaymentBankMutator extends BaseMutator
{
    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        $bank = PaymentBank::create($args);

        if (isset($args['currency_id'])) {
            $bank->paymentBankCurrencies()->delete();

            foreach ($args['currency_id'] as $id) {
                $bank->paymentBankCurrencies()->create(['currency_id' => $id]);
            }
        }

        if (isset($args['region_id'])) {
            $bank->paymentBankRegions()->delete();

            foreach ($args['region_id'] as $id) {
                $bank->paymentBankRegions()->create(['region_id' => $id]);
            }
        }

        return $bank;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $bank = PaymentBank::find($args['id']);
        if (! $bank) {
            throw new GraphqlException('Not found', 'not found', 404);
        }
        $bank->update($args);

        if (isset($args['currency_id'])) {
            $bank->paymentBankCurrencies()->delete();

            foreach ($args['currency_id'] as $id) {
                $bank->paymentBankCurrencies()->create(['currency_id' => $id]);
            }
        }

        if (isset($args['region_id'])) {
            $bank->paymentBankRegions()->delete();

            foreach ($args['region_id'] as $id) {
                $bank->paymentBankRegions()->create(['region_id' => $id]);
            }
        }

        return $bank;
    }
}
