<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait UserPermission
{
    public function allPermissions(): Collection
    {
        return $this->groupRoles->map(function ($group){
            return $group->role->permissions;
        })->flatten();
    }

    public function hasPermission($model, $name): bool
    {
        $this->loadRolesAndPermissionsRelations();

        return (bool) $this->allPermissions()->where('model', $model)->where('action_type', $name)->count();
    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRoles.role.permissions');
    }
}
