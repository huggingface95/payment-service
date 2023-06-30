<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CommissionPriceList;
use App\Models\CommissionTemplate;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;

class CommissionPriceListMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($root, array $args)
    {
        if ($args['payment_system_id']) {
            $paymentSystem = PaymentSystem::find($args['payment_system_id']);
            $existingInternalProvider = CommissionPriceList::where('payment_system_id', $args['payment_system_id'])->exists();
            $commissionTemplate = CommissionTemplate::find($args['commission_template_id']);

            if ($paymentSystem && $paymentSystem->name == PaymentSystem::NAME_INTERNAL) {
                if ($existingInternalProvider && $commissionTemplate->paymentProvider->name == PaymentProvider::NAME_INTERNAL) {
                    throw new GraphqlException('Only one Commission Price List with Internal Payment Provider is allowed', 'use');
                }
            }
            if ($paymentSystem->name == PaymentSystem::NAME_INTERNAL && $commissionTemplate->paymentProvider->name != PaymentProvider::NAME_INTERNAL) {
                throw new GraphqlException('Only one Internal PriceList is allowed for External Commission Template', 'use');
            }
        }

        if (array_key_exists('region_id', $args) && $args['commission_template_id'] && !$this->isUniqueProviderRegionCombination($args['commission_template_id'], $args['payment_system_id'], $args['region_id'])) {
            throw new GraphqlException('Commission Template with the same PaymentSystem and Region already exists', 'use');
        }

        $commissionPriceList = CommissionPriceList::create($args);

        return $commissionPriceList;
    }

    public function update($root, array $args)
    {
        $commissionPriceList = CommissionPriceList::findOrFail($args['id']);

        if (isset($args['payment_system_id']) && isset($args['commission_template_id'])) {
            $paymentSystem = PaymentSystem::find($args['payment_system_id']);
            $existingInternalProvider = CommissionPriceList::where('payment_system_id', $args['payment_system_id'])->exists();
            $commissionTemplate = CommissionTemplate::find($args['commission_template_id']);

            if ($paymentSystem && $paymentSystem->name == PaymentSystem::NAME_INTERNAL) {
                if ($existingInternalProvider && $commissionTemplate->paymentProvider->name == PaymentProvider::NAME_INTERNAL) {
                    throw new GraphqlException('Only one Commission Price List with Internal Payment Provider is allowed', 'use');
                }
            }
            if ($commissionPriceList->paymenSystem?->name == PaymentSystem::NAME_INTERNAL && $commissionTemplate->paymentProvider?->name != PaymentProvider::NAME_INTERNAL) {
                throw new GraphqlException('Only one Commission Price List with Internal Payment Provider is allowed for External Commission Template', 'use');
            }
            if (array_key_exists('region_id', $args) && $args['commission_template_id'] && !$this->isUniqueProviderRegionCombination($args['commission_template_id'], $args['payment_system_id'], $args['region_id'])) {
                throw new GraphqlException('Commission Template with the same PaymentSystem and Region already exists', 'use');
            }
        }

        $commissionPriceList->update($args);

        return $commissionPriceList;
    }

    private function isUniqueProviderRegionCombination($commissionTemplateId, $paymentSystemId, $regionId)
    {
        return CommissionPriceList::where('commission_template_id', $commissionTemplateId)
            ->where('payment_system_id', $paymentSystemId)
            ->where('region_id', $regionId)
            ->doesntExist();
    }
}
