<?php

namespace App\GraphQL\Queries;

use App\Models\Module;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ModuleQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function nokyc($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Module::where('name', '!=', 'KYC')->get();
    }
}
