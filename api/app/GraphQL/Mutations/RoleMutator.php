<?php

namespace App\GraphQL\Mutations;

use App\Models\Roles;

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

        if(isset($args['groups'])) {
            foreach ($args['groups'] as $group) {
                $role->addGroup($group);
            }
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
        if(isset($args['groups'])) {
            foreach ($args['groups'] as $group) {
                $role->addGroup($group);
            }
        }

        $role->update($args);
        return $role;
    }

    /**
     * @param Roles $role
     * @param $permissions
     * @return Roles
     */
    private function syncPermissions(Roles $role, $permissions)
    {
        $assing = [];
        foreach ($permissions ?? [] as $item) {
            foreach ($item['rules'] ?? [] as $permission)
                $assing[] = $item['entity'] . '.' . $permission;
        }
        return $role->syncPermissions($assing);
    }


}
