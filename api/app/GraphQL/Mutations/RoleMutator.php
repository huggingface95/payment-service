<?php

namespace App\GraphQL\Mutations;

use App\Models\Roles;
use Illuminate\Support\Facades\Hash;


class RoleMutator
{


    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        $role = Roles::create($args);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }

        return $role;
    }

    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $role = Roles::find($args['id']);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }

        $role->update($args);
        return $role;
    }

    private function syncPermissions(Roles $role, $permissions)
    {
        $permissions = json_decode($permissions,true);
        $assing = [];
        foreach ($permissions ?? [] as $item) {
            foreach ($item['rules'] ?? [] as $permission)
                $assing[] = $item['entity'] . '.' . $permission;
        }
        return $role->syncPermissions($assing);
    }


}
