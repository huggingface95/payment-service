<?php

namespace App\GraphQL\Queries;

use App\Models\Accounts;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use App\Models\GroupType;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AccountsQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id'=>$args['company_id'], 'group_role_id'=>$args['group_role_id'], 'group_type_id'=>$args['group_type_id']];

        return Accounts::paginate($args['paginate']['count']);
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function clientList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($args['group_type'] == GroupRole::COMPANY) {
            return ApplicantCompany::get();
        } else {
            return ApplicantIndividual::get();
        }
    }
}
