<?php

namespace App\GraphQL\Queries;

use App\Models\GroupRole;
use App\Models\GroupType;

class GroupsQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args)
    {
        if (isset($args['mode']) && $args['mode'] === 'clients') {
            return GroupType::where('id', '!=', GroupRole::MEMBER)->get();
        } else {
            return GroupType::get();
        }
    }
}
