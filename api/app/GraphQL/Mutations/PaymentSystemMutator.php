<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PaymentSystem;

class PaymentSystemMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($_, array $args)
    {
        try {
            $paymentSystem = PaymentSystem::find($args['id']);
            $paymentSystem->delete();

            return $paymentSystem;
        } catch (\Exception $exception) {
            throw new GraphqlException('Payment system was not deleted. Please, check if it`s assigned to a Payment Provider.', 'use');
        }
    }

    public function attachRespondentFee($root, array $args): PaymentSystem
    {
        $paymentSystem = PaymentSystem::find($args['payment_system_id']);
        if (! $paymentSystem) {
            throw new GraphqlException('Payment system not found', 'not found', 404);
        }

        $paymentSystem->respondentFees()->detach();
        $paymentSystem->respondentFees()->attach($args['respondent_fee_id']);

        return $paymentSystem;
    }

    public function detachRespondentFee($root, array $args): PaymentSystem
    {
        $paymentSystem = PaymentSystem::find($args['payment_system_id']);
        if (! $paymentSystem) {
            throw new GraphqlException('Payment system not found', 'not found', 404);
        }

        $paymentSystem->respondentFees()->detach($args['respondent_fee_id']);

        return $paymentSystem;
    }
}
