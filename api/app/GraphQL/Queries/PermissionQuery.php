<?php

namespace App\GraphQL\Queries;

use App\Models\Permissions;

class PermissionQuery
{
    public function tree($_, array $args)
    {
        $args['all'] = Permissions::getTreePermissions();

        return $args;
    }
}
