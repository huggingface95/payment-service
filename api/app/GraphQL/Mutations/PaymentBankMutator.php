<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BankCorrespondent;
use App\Models\PaymentBank;
use Illuminate\Support\Facades\DB;

class PaymentBankMutator extends BaseMutator
{
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

            $bank = PaymentBank::find($args['id']);
            if (!$bank) {
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
