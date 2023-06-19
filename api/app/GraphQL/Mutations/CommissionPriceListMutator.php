<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CommissionPriceList;
use App\Models\PaymentProvider;

class CommissionPriceListMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($root, array $args)
    {
        if ($args['provider_id']) {
            $paymentProvider = PaymentProvider::find($args['provider_id']);
            if ($paymentProvider && $paymentProvider->name === 'Internal') {
                $existingInternalProvider = CommissionPriceList::where('provider_id', $args['provider_id'])->exists();
                if ($existingInternalProvider) {
                    throw new GraphqlException('Only one Commission Price List with Internal Payment Provider is allowed', 'use');
                }
            }
        }

        if (array_key_exists('region_id', $args) && $args['commission_template_id'] && !$this->isUniqueProviderRegionCombination($args['commission_template_id'], $args['provider_id'], $args['region_id'])) {
            throw new GraphqlException('Commission Template with the same Provider and Region already exists', 'use');
        }

        $commissionPriceList = CommissionPriceList::create($args);

        return $commissionPriceList;
    }

    public function update($root, array $args)
    {
        $commissionPriceList = CommissionPriceList::findOrFail($args['id']);

        if ($args['provider_id']) {
            $paymentProvider = PaymentProvider::find($args['provider_id']);
            if ($paymentProvider && $paymentProvider->name === 'Internal') {
                $existingInternalProvider = CommissionPriceList::where('provider_id', $args['provider_id'])->exists();
                if ($existingInternalProvider) {
                    throw new GraphqlException('Only one Commission Price List with Internal Payment Provider is allowed', 'use');
                }
            }
        }

        if (array_key_exists('region_id', $args) && $args['commission_template_id'] && !$this->isUniqueProviderRegionCombination($args['commission_template_id'], $args['provider_id'], $args['region_id'])) {
            throw new GraphqlException('Commission Template with the same Provider and Region already exists', 'use');
        }

        $commissionPriceList->update($args);

        return $commissionPriceList;
    }

    private function isUniqueProviderRegionCombination($commissionTemplateId, $providerId, $regionId)
    {
        return CommissionPriceList::where('commission_template_id', $commissionTemplateId)
            ->where('provider_id', $providerId)
            ->where('region_id', $regionId)
            ->doesntExist();
    }
}
