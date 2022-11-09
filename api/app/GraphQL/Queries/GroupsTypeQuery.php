<?php

namespace App\GraphQL\Queries;

use App\Models\GroupRole;
use App\Models\GroupType;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GroupsTypeQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args, ?GraphQLContext $context, ResolveInfo $resolveInfo):Builder
    {
        if (isset($args['mode']) && $args['mode'] === 'clients') {
            return GroupType::query()->where('id', '!=', GroupRole::MEMBER);
        } else {
            return GroupType::query();
        }
    }
}
