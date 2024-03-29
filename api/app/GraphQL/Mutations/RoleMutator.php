<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\GroupRole;
use App\Models\Members;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleMutator
{
    /**
     * @param $_
     * @param  array  $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        if (isset($args['groups']) && $args['groups'][0] == '2') {
            throw new GraphqlException('Role is not be used for this group', 'internal', 500);
        }
        /** @var Members $member */
        $member = Auth::user();

        /** @var Role $role */
        $role = Role::create($args);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }
        if (isset($args['groups'])) {
            $this->syncGroups($role, $args['groups']);
        }
        if (isset($args['permission_category_all_member']) && $member->is_super_admin) {
            $role->permissionCategories()->attach($args['permission_category_all_member']);
        }

        return $role;
    }

    /**
     * @param $_
     * @param  array  $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        if (isset($args['groups']) && $args['groups'][0] == '2') {
            throw new GraphqlException('Role is not be used for this group', 'internal', 500);
        }
        /** @var Members $member */
        $member = Auth::user();

        $role = Role::find($args['id']);
        if (isset($args['permissions'])) {
            $this->syncPermissions($role, $args['permissions']);
        }
        if (isset($args['groups'])) {
            $this->syncGroups($role, $args['groups']);
        }

        if (isset($args['permission_category_all_member']) && $member->is_super_admin) {
            $role->permissionCategories()->detach();
            $role->permissionCategories()->attach($args['permission_category_all_member']);
        }

        $role->update($args);

        return $role;
    }

    /**
     * @param  Role  $role
     * @param $permissions
     * @return Role
     */
    private function syncPermissions(Role $role, $permissions)
    {
//        $permissionsName = Permissions::getPermissionArrayNamesById($permissions);
//        dd($permissionsName);
        return $role->syncPermissions($permissions);
    }

    private function syncGroups(Role $role, array $groups)
    {
        foreach ($groups as $group) {
            GroupRole::where('id', $group)->update(['role_id'=>$role->id]);
        }
    }

    private function applyPermissionCategory(int $permissionCategoryId, bool $isAllCompanies)
    {
        return PermissionCategory::where('id', $permissionCategoryId)->update(['is_all_companies'=>$isAllCompanies]);
    }

    public function delete($root, array $args)
    {
        try {
            $role = Role::find($args['id']);
            $group = GroupRole::all()->where('role_id', '=', $args['id']);
            if ($group != '[]') {
                throw new GraphqlException('It is not possible to delete the role because it has active users', 'use');
            }
            $role->delete();

            return $role;
        } catch (\Exception $exception) {
            throw new GraphqlException('It is not possible to delete the role because it has active users', 'use');
        }
    }
}
