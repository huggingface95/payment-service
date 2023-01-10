<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Module;

class ModuleMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param
     * @param  array
     * @return mixed
     */
    public function create($root, array $args)
    {
        if (! auth()->user()->is_super_admin) {
            throw new GraphqlException('Access denied', 'use', 403);
        }

        $module = Module::create($args);

        return $module;
    }
}
