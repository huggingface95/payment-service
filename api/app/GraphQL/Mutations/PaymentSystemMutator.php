<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PaymentSystem;
use App\Services\CompanyRevenueAccountService;
use Illuminate\Support\Facades\DB;

class PaymentSystemMutator
{
    public function __construct(protected CompanyRevenueAccountService $companyRevenueAccountService)
    {
    }

    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): PaymentSystem
    {
        try {
            DB::beginTransaction();

            /** @var PaymentSystem $paymentSystem */
            $paymentSystem = PaymentSystem::query()->create($args);

            $this->syncRelations($paymentSystem, $args);

            DB::commit();

            return $paymentSystem;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): PaymentSystem
    {
        try {
            DB::beginTransaction();

            /** @var PaymentSystem $paymentSystem */
            $paymentSystem = PaymentSystem::query()->find($args['id']);

            $paymentSystem->update($args);

            $this->syncRelations($paymentSystem, $args);

            DB::commit();

            return $paymentSystem;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

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

    protected function syncRelations(PaymentSystem $paymentSystem, $args): void
    {
        if (isset($args['regions'])) {
            $paymentSystem->regions()->sync($args['regions']['sync']);
        }
        if (isset($args['operations'])) {
            $paymentSystem->operations()->sync($args['operations']['sync']);
        }
        if (isset($args['currencies'])) {
            $paymentSystem->currencies()->sync($args['currencies']['sync']);
            $this->companyRevenueAccountService->sync($paymentSystem, $args['currencies']['sync']);
        }
    }
}
