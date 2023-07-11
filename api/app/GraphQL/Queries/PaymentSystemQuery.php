<?php

namespace App\GraphQL\Queries;

use App\Models\PaymentSystem;

final class PaymentSystemQuery
{
    /**
     * Get data with pagination and filteration
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args)
    {
        $paymentSystems = PaymentSystem::get()->unique('name');

        return $paymentSystems;
    }

    /**
     * Get PS regions
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getRegions($_, array $args)
    {
        $paymentSystem = PaymentSystem::findOrFail($args['id']);

        if ($paymentSystem->name != PaymentSystem::NAME_INTERNAL) {
            $paymentSystemRegions = $paymentSystem->banks()
                ->with('regions')
                ->get()
                ->pluck('regions')
                ->flatten()
                ->unique();
        } else {
            $paymentProvider = $paymentSystem->providers()->first();
            $paymentSystems = PaymentSystem::where('payment_provider_id', $paymentProvider->id)
                ->where('name', '!=', PaymentSystem::NAME_INTERNAL)
                ->pluck('id')
                ->unique();

            $paymentSystemRegions = PaymentSystem::whereIn('id', $paymentSystems)->with('regions')->get()->flatMap(function ($paymentSystem) {
                return $paymentSystem->regions;
            })->unique();
        }

        return $paymentSystemRegions;
    }

    /**
     * Get PS currencies
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getCurrencies($_, array $args)
    {
        $paymentSystem = PaymentSystem::findOrFail($args['id']);

        if ($paymentSystem->name != PaymentSystem::NAME_INTERNAL) {
            $paymentSystemCurrencies = $paymentSystem->banks()
                ->with('currencies')
                ->get()
                ->pluck('currencies')
                ->flatten()
                ->unique();
        } else {
            $paymentProvider = $paymentSystem->providers()->first();
            $paymentSystems = PaymentSystem::where('payment_provider_id', $paymentProvider->id)
                ->where('name', '!=', PaymentSystem::NAME_INTERNAL)
                ->pluck('id')
                ->unique();

            $paymentSystemCurrencies = PaymentSystem::whereIn('id', $paymentSystems)->with('currencies')->get()->flatMap(function ($paymentSystem) {
                return $paymentSystem->currencies;
            })->unique();
        }

        return $paymentSystemCurrencies;
    }
}
