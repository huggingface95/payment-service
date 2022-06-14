<?php

namespace App\GraphQL\Queries;

use App\Models\EmailNotification;
use App\Models\Groups;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class EmailNotificationQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id'=>$args['company_id'], 'group_role_id'=>$args['group_role_id'],'group_type_id'=>$args['group_type_id']];
        if (isset($args['client_id']))
        {
            if($args['group_type_id'] == Groups::MEMBER)
                $relation = 'member';
            elseif ($args['group_type_id'] == Groups::COMPANY)
                $relation = 'applicantCompany';
            elseif ($args['group_type_id'] == Groups::INDIVIDUAL)
                $relation = 'applicantIndividual';
            $clientId = $args['client_id'];
            return  EmailNotification::whereHas($relation, function (Builder $q) use ($clientId){
                $q->where($q->getModel()->getTable().". id", "=", $clientId);
            })->where($condition);

        } else {
            return EmailNotification::where($condition)->first();
        }
    }

}
