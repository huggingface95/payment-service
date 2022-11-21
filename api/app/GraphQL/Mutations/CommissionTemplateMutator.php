<?php

namespace App\GraphQL\Mutations;

use App\Models\CommissionTemplate;
use App\Models\CommissionTemplateLimit;

class CommissionTemplateMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($root, array $args)
    {
        $memberId = CommissionTemplate::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $commissionTemplate = CommissionTemplate::create($args);

        return $commissionTemplate;
    }

    public function update($root, array $args)
    {
        /** @var CommissionTemplate $commissionTemplate */
        $commissionTemplate = CommissionTemplate::find($args['id']);
        $memberId = CommissionTemplate::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
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
        $commissionTemplate->update($args);

        return $commissionTemplate;
    }
}
