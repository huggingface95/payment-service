<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\CommissionTemplateLimit;

class CommissionTemplateLimitMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($root, array $args)
    {
        $commissionTemplateLimit = CommissionTemplateLimit::where('commission_template_limit_type_id', $args['commission_template_limit_type_id'])
            ->where('commission_template_limit_transfer_direction_id', $args['commission_template_limit_transfer_direction_id'])
            ->where('currency_id', $args['currency_id'])
            ->where('commission_template_limit_period_id', $args['commission_template_limit_period_id'])
            ->where('commission_template_limit_action_type_id', $args['commission_template_limit_action_type_id'])
            ->where('commission_template_id', $args['commission_template_id'])
            ->first();

        if (! $commissionTemplateLimit) {
            $commissionTemplate = CommissionTemplateLimit::create($args);
        }
        else {
            throw new GraphqlException('Threshold already exist', 'use');
        }

        return $commissionTemplate;
    }

    public function update($root, array $args)
    {
        /** @var CommissionTemplateLimit $commissionTemplateLimit */
        $commissionTemplateLimit = CommissionTemplateLimit::find($args['id']);
        $findCommissionTemplateLimit = CommissionTemplateLimit::where('commission_template_limit_type_id', $args['commission_template_limit_type_id'])
            ->where('commission_template_limit_transfer_direction_id', $args['commission_template_limit_transfer_direction_id'])
            ->where('currency_id', $args['currency_id'])
            ->where('commission_template_limit_period_id', $args['commission_template_limit_period_id'])
            ->where('commission_template_limit_action_type_id', $args['commission_template_limit_action_type_id'])
            ->where('commission_template_id', $args['commission_template_id'])
            ->first();

        if (! $findCommissionTemplateLimit) {
            $commissionTemplateLimit->update($args);
        } else {
            throw new GraphqlException('Threshold already exist', 'use');
        }

        return $commissionTemplateLimit;
    }
}
