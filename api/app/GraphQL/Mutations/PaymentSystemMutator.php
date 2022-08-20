<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PaymentSystem;
use Illuminate\Support\Facades\DB;

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
            DB::table('payment_provider_payment_system')->where('payment_system_id', $args['id'])->delete();
            $paymentSystem->delete();

            return $paymentSystem;
        } catch (\Exception $exception) {
            throw new GraphqlException('Payment system was not deleted. Please, check if it`s assigned to a Payment Provider.', 'use');
        }
    }
}
