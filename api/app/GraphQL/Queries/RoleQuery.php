<?php

namespace App\GraphQL\Queries;

use App\Models\Permissions;
use App\Models\Role;
use GraphQL\Exception\InvalidArgument;

class RoleQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function permissions($_, array $args)
    {
        try {
            $args = Role::findById($args['id']);
            $args['permisssions_tree'] = Permissions::getTreePermissions($args['id']);
            return $args;
        }
        catch (InvalidArgument $exception) {
            return $exception->getMessage();
        }

    }

}
