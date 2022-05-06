<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait UserPermission
{
    public function allPermissions(string $model): Collection
    {
        return $this->groupRoles->pluck('role.permissions')
            ->flatten()->unique();
    }

    public function hasPermission($model, $name): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $allPermissions = $this->allPermissions($model);

        $permission = $allPermissions
            ->where('model', $model)
            ->where('action_type', $name)
            ->first();

        if ($permission) {
            if (null == $permission->parent) {
                return true;
            } elseif ($allPermissions->where('id', $permission->parent->id)->count()) {
                return true;
            }
        }
        return false;

    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRoles.role.permissions.parent.children');
    }
}
