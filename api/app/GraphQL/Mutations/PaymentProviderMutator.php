<?php

namespace App\GraphQL\Mutations;

use App\Models\PaymentProvider;
use App\Models\PaymentSystem;

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
            $getSystem = PaymentSystem::whereIn('id', $paymentSystems)->get();
            unset($args['payment_systems']);
            $paymentProvider = PaymentProvider::create($args);
            $paymentProvider->paymentSystems()->saveMany($getSystem);
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
            $paymentSystems = $args['payment_systems'];
            $getSystem = PaymentSystem::whereIn('id', $paymentSystems)->get();
            unset($args['payment_systems']);
            $paymentProvider->paymentSystems()->saveMany($getSystem);
        }
        $paymentProvider->update($args);

        return $paymentProvider;
    }
}
