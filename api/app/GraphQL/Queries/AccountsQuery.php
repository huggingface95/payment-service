<?php

namespace App\GraphQL\Queries;

use App\Models\Accounts;
use App\Models\GroupRole;
use App\Models\Groups;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AccountsQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id'=>$args['company_id'], 'group_role_id'=>$args['group_role_id'],'group_type_id'=>$args['group_type_id']];
        return Accounts::paginate($args['paginate']['count']);
    }

}
