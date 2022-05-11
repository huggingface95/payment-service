<?php

namespace App\Models\Traits;

use App\Models\PermissionOperation;
use Illuminate\Support\Collection;

trait UserPermission
{
    public function allPermissions(): Collection
    {
        return $this->groupRoles->pluck('role.permissions')
            ->flatten()->unique();
    }

    public function hasPermission(string $name, string $url): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $allPermissions = $this->allPermissions();

        $operation = PermissionOperation::query()->with(['parents', 'binds'])
            ->where('hidden', false)
            ->where('name', $name)
            ->where('referer', $url)
            ->first();

        if ($operation) {
            $bindPermissions = $operation->binds->intersect($allPermissions);

            if ($bindPermissions->count()) {
                if (!$operation->parents->count()) {
                    return true;
                }
                $parentPermissions = $operation->parents->intersect($allPermissions);
                if ($parentPermissions->count()) {
                    return true;
                }
            }
        }
        else{
            //Global operations
            if (PermissionOperation::query()->where('hidden', true)->where('name', $name)->count()){
                return true;
            }
        }
        return false;

    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRoles.role.permissions');
    }
}