<?php

namespace App\GraphQL\Mutations;

use App\Models\GroupRole;
use App\Models\Permissions;
use App\Models\Role;
use App\Exceptions\GraphqlException;

class RoleMutator
{


    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        if ($args['groups'][0] == '2') {
            throw new GraphqlException('Role is not be used for this group',"internal", 500);
        }

        $role = Role::create($args);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }
        if (isset($args['groups'])) {
            $this->syncGroups($role, $args['groups']);
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
        if ($args['groups'][0] == '2') {
            throw new GraphqlException('Role is not be used for this group',"internal", 500);
        }

        $role = Role::find($args['id']);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }
        if (isset($args['groups'])) {
            $this->syncGroups($role, $args['groups']);
        }

        $role->update($args);

        return $role;
    }

    /**
     * @param Role $role
     * @param $permissions
     * @return Role
     */
    private function syncPermissions(Role $role, $permissions)
    {
        $permissionsName = Permissions::getPermissionArrayNamesById($permissions);

        return $role->syncPermissions($permissionsName);
    }

    private function syncGroups(Role $role, array $groups)
    {
        foreach ($groups as $group) {
            GroupRole::where('id',$group)->update(['role_id'=>$role->id]);
        }
    }

}
