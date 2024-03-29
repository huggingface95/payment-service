<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Company;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use Illuminate\Support\Facades\DB;

class PaymentProviderMutator
{
    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): PaymentProvider
    {
        DB::beginTransaction();
        try {
            /** @var Company $company */
            $company = Company::query()->with('paymentSystemInternal')->findOrFail($args['company_id']);

            if (! $company->paymentSystemInternal) {
                throw new GraphqlException('Payment System Internal not found in company', 'use');
            }
            $paymentProvider = PaymentProvider::query()->create($args);

            PaymentSystem::query()->create(
                [
                    'name' => PaymentSystem::NAME_INTERNAL,
                    'payment_provider_id' => $paymentProvider->id,
                    'is_active' => true,
                ]
            );

            DB::commit();

            return $paymentProvider;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $paymentProvider = PaymentProvider::find($args['id']);
        if (isset($args['payment_systems']) && count($args['payment_systems'])) {
            $paymentSystems = $args['payment_systems'];
            $getSystem = PaymentSystem::whereIn('id', $paymentSystems)->get();
            if ($getSystem->isEmpty()) {
                throw new GraphqlException('Payment System does not exist', 'use');
            } else {
                unset($args['payment_systems']);
                $paymentProvider->paymentSystems()->saveMany($getSystem);
            }
        }
        $paymentProvider->update($args);

        return $paymentProvider;
    }
}
