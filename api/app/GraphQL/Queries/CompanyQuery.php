<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CompanyQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
    }
}
