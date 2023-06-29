<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\CommissionTemplate;
use App\Models\CommissionTemplateLimit;
use App\Models\PaymentSystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommissionTemplateMutator
{

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): CommissionTemplate
    {
        try {
            DB::beginTransaction();

            $args['member_id'] = Auth::guard('api')->user()->id;

            /** @var CommissionTemplate $commissionTemplate */
            $commissionTemplate = CommissionTemplate::query()->create($args);

            if (isset($args['payment_provider_id']) && isset($args['payment_system_id'])) {
                $this->updatePaymentProvider($args);
            }

            if (isset($args['business_activity'])) {
                $commissionTemplate->businessActivity()->attach($args['business_activity']);
                unset($args['business_activity']);
            }
            DB::commit();

            return $commissionTemplate;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): CommissionTemplate
    {
        try {
            DB::beginTransaction();

            /** @var CommissionTemplate $commissionTemplate */
            $commissionTemplate = CommissionTemplate::find($args['id']);
            $args['member_id'] = Auth::guard('api')->user()->id;
            if (isset($args['business_activity'])) {
                $commissionTemplate->businessActivity()->detach();
                $commissionTemplate->businessActivity()->attach($args['business_activity']);
                unset($args['business_activity']);
            }
            if (isset($args['commission_template_limit_id'])) {
                $limits = CommissionTemplateLimit::whereIn('id', $args['commission_template_limit_id'])->get();
                $commissionTemplate->commissionTemplateLimits()->saveMany($limits);
                unset($args['commission_template_limit_id']);
            }
            if (isset($args['currency_id'])) {
                $commissionTemplate->currencies()->detach();
                $commissionTemplate->currencies()->attach($args['currency_id']);
                unset($args['currency_id']);
            }
            if (isset($args['region_id'])) {
                $commissionTemplate->regions()->detach();
                $commissionTemplate->regions()->attach($args['region_id']);
                unset($args['region_id']);
            }
            if (isset($args['payment_provider_id']) && isset($args['payment_system_id'])) {
                $this->updatePaymentProvider($args);
            }

            $commissionTemplate->update($args);

            DB::commit();

            return $commissionTemplate;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function updatePaymentProvider($args): void
    {
        $paymentSystem = PaymentSystem::query()->whereIn('id', $args['payment_system_id'])->count();
        if (count($args['payment_system_id']) != $paymentSystem) {
            throw new GraphqlException('Payment system not exists.', 'use');
        }

        PaymentSystem::query()->whereIn('id', $args['payment_system_id'])
            ->update(['payment_provider_id' => $args['payment_provider_id']]);
    }
}
