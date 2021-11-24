<?php

namespace App\GraphQL\Mutations;

use App\Models\CommissionTemplate;

class CommissionTemplateMutator
{

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $commissionTemplate = CommissionTemplate::find($args['id']);
        if (isset($args['business_activity'])) {
            $commissionTemplate->businessActivity()->detach();
            $commissionTemplate->businessActivity()->attach($args['business_activity']);
            unset($args['business_activity']);
        }
        if (isset($args['commission_template_limit_id'])) {
            $commissionTemplate->commissionTemplateLimits()->detach();
            $commissionTemplate->commissionTemplateLimits()->attach($args['commission_template_limit_id']);
            unset($args['commission_template_limit_id']);
        }
        if (isset($args['currency_id'])) {
            $commissionTemplate->currencies()->detach();
            $commissionTemplate->currencies()->attach($args['currency_id']);
            unset($args['currency_id']);
        }
        if (isset($args['country_id'])) {
            $commissionTemplate->countries()->detach();
            $commissionTemplate->countries()->attach($args['country_id']);
            unset($args['country_id']);
        }
        $commissionTemplate->update($args);
        return $commissionTemplate;
    }
}
