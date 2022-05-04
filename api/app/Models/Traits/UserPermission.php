<?php

namespace App\Models\Traits;

trait UserPermission
{
    public function allPermissions()
    {
        return $this->roles->pluck('permissions');
    }

    public function hasPermission($name): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $permissions = $this->allPermissions()
            ->flatten()
            ->pluck('action_type')
            ->unique()->toArray();

        return in_array($name, $permissions);
    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('roles.permissions');
    }
}
