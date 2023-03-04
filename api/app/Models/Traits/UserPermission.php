<?php

namespace App\Models\Traits;

use App\Models\PermissionOperation;
use Illuminate\Support\Collection;

trait UserPermission
{
    public function getAllPermissions(): Collection
    {
        $this->loadRolesAndPermissionsRelations();

        return $this->groupRole->role->permissions ?? collect([]);
    }

    public function getAllPermissionsList(): Collection
    {
        $this->loadPermissionsList();

        return $this->groupRole->role->permissions->groupBy('permissionList.name',function ($permission){
            return $permission->permissionList->name;
        })->map(function ($permissions){
            return $permissions->pluck('id', 'display_name');
        }) ?? collect([]);
    }

    public function hasPermission(string $name, string $url): bool
    {
        $allPermissions = $this->getAllPermissions();

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
            ->where('referer', preg_replace("/([0-9]+)/", '$id', $url))
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

    private function loadRolesAndPermissionsRelations(): void
    {
        $this->load('groupRole.role.permissions');
    }

    private function loadPermissionsList(): void
    {
        $this->load('groupRole.role.permissions.permissionList');
    }
}
