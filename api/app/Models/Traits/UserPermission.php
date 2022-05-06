<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait UserPermission
{
    public function allPermissions(): Collection
    {
        return $this->groupRoles->pluck('role.permissions')
            ->flatten()->unique();
    }

    public function hasPermission($name): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $allPermissions = $this->allPermissions();

        $permission = $allPermissions
            ->where('action_type', $name)->first();


        if ($permission) {
            if (!$permission->parents->count()) {
                return true;
            } elseif ($allPermissions->whereIn('id', $permission->parents->pluck('id'))->count()) {
                return true;
            }
        }
        return false;

    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRoles.role.permissions.parents');
    }
}
