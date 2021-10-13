<?php

namespace App\GraphQL\Mutations;

use App\Models\PaymentProvider;

class PaymentProviderMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {
        if (isset($args['payment_systems'])) {
            $paymentSystems = $args['payment_systems'];
            unset($args['payment_systems']);
            $paymentProvider = PaymentProvider::create($args);
            $paymentProvider->paymentSystems()->attach($paymentSystems);
        } else {
            $paymentProvider = PaymentProvider::create($args);
        }
        return $paymentProvider;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $paymentProvider = PaymentProvider::find($args['id']);
        if (isset($args['payment_systems'])) {
            $paymentProvider->paymentSystems()->detach();
            $paymentProvider->paymentSystems()->attach($args['payment_systems']);
            unset($args['payment_systems']);
        }
        $paymentProvider->update($args);
        return $paymentProvider;
    }
}
