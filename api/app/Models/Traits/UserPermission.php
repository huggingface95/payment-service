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

    public function hasPermission($name, $url): bool
    {
        $this->loadRolesAndPermissionsRelations();

        $allPermissions = $this->allPermissions();

        $permission = $allPermissions->filter(function ($p) use ($name, $url) {
            return strstr($name, $p->action_type) && strstr($p->referer, $url);
        })->first();


        if ($permission) {
            if (!$permission->parents->count()) {
                return true;
            }
            $currentParents = $allPermissions->whereIn('id', $permission->parents->pluck('id'));
            if ($currentParents->count()) {
                foreach ($currentParents as $p) {
                    if (strstr($name, $p->action_type)) {
                        return true;
                    }
                }
            }
        }
        return false;

    }

    private function loadRolesAndPermissionsRelations()
    {
        $this->load('groupRoles.role.permissions.parents');
    }
}
