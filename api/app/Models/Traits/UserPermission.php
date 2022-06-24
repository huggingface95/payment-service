<?php

namespace App\Models\Traits;

use App\Models\PermissionOperation;

trait UserPermission
{
    public function hasPermission(string $name, string $url): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $allPermissions = $this->groupRole->role->permissions;

        //global permissions
        if (PermissionOperation::query()
            ->whereNull('referer')
            ->where('name', $name)
            ->whereDoesntHave('parents')
            ->whereDoesntHave('binds')
            ->first()
        ) {
            return true;
        }

        $operation = PermissionOperation::query()->with(['parents', 'binds'])
            ->where('name', $name)
            ->where('referer', $url)
            ->first();

        if ($operation) {
            $bindPermissions = $operation->binds->intersect($allPermissions);

            if ($bindPermissions->count()) {
                if (! $operation->parents->count()) {
                    return true;
                }
                $parentPermissions = $operation->parents->intersect($allPermissions);
                if ($parentPermissions->count()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRole.role.permissions');
    }
}
