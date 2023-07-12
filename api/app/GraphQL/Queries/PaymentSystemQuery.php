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
            $paymentBankRegions = $paymentSystem->banks()
                ->with('regions')
                ->get()
                ->pluck('regions')
                ->flatten()
                ->unique();

            $bankCorrespondentRegions = $paymentSystem->bankCorrespondent()
                ->with('regions')
                ->get()
                ->pluck('regions')
                ->flatten()
                ->unique();

            $paymentSystemRegions = $paymentBankRegions->merge($bankCorrespondentRegions)->unique('id')->sortBy('id');
        } else {
            $paymentProvider = $paymentSystem->providers()->first();
            $paymentSystems = PaymentSystem::where('payment_provider_id', $paymentProvider->id)
                ->where('name', '!=', PaymentSystem::NAME_INTERNAL)
                ->pluck('id')
                ->unique();

            $paymentSystemRegions = PaymentSystem::whereIn('id', $paymentSystems)
                ->get()
                ->flatMap(function ($paymentSystem) {
                    $paymentBankRegions = $paymentSystem->banks->pluck('regions')->flatten()->unique();
                    $bankCorrespondentRegions = $paymentSystem->bankCorrespondent->regions()->get()->unique();
                    return $paymentBankRegions->merge($bankCorrespondentRegions);
                })
                ->unique('id')
                ->sortBy('id');
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
            $paymentBankCurrencies = $paymentSystem->banks()
                ->with('currencies')
                ->get()
                ->pluck('currencies')
                ->flatten()
                ->unique();

            $bankCorrespondentCurrencies = $paymentSystem->bankCorrespondent()
                ->with('currencies')
                ->get()
                ->pluck('currencies')
                ->flatten()
                ->unique();

            $paymentSystemCurrencies = $paymentBankCurrencies->merge($bankCorrespondentCurrencies)->unique('id')->sortBy('id');
        } else {
            $paymentProvider = $paymentSystem->providers()->first();
            $paymentSystems = PaymentSystem::where('payment_provider_id', $paymentProvider->id)
                ->where('name', '!=', PaymentSystem::NAME_INTERNAL)
                ->pluck('id')
                ->unique();

            $paymentSystemCurrencies = PaymentSystem::whereIn('id', $paymentSystems)
                ->get()
                ->flatMap(function ($paymentSystem) {
                    $paymentBankCurrencies = $paymentSystem->banks->pluck('currencies')->flatten()->unique();
                    $bankCorrespondentCurrencies = $paymentSystem->bankCorrespondent->currencies()->get()->unique();
                    return $paymentBankCurrencies->merge($bankCorrespondentCurrencies);
                })
                ->unique('id')
                ->sortBy('id');
        }

        return $paymentSystemCurrencies;
    }
}
