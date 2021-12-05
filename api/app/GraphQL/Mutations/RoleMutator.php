<?php

namespace App\GraphQL\Mutations;

use App\Models\GroupRole;
use App\Models\Permissions;
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
        $role = Roles::find($args['id']);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }
        if(isset($args['groups'])) {
            $this->syncGroups($role, $args['groups']);
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
        $permissionsName = Permissions::getPermissionArrayNamesById($permissions);

        return $role->syncPermissions($permissionsName);
    }

    private function syncGroups(Roles $role, array $groups)
    {
        $currentGroups = $role->getGroupsIdByRole();
        $groupsDelete = array_diff($currentGroups,$groups);
        if ($groupsDelete) {
            GroupRole::where('role_id',$role->id)->whereIn('group_id',$groupsDelete)->delete();
        }
        foreach ($groups as $group) {
            GroupRole::updateOrCreate(['role_id'=>$role->id, 'group_id'=>$group],['group_id'=> $group]);
        }
    }


}
