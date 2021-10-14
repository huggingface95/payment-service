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
        $commissionTemplate->update($args);
        return $commissionTemplate;
    }
}
