<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait UserPermission
{
    public function getAllPermissions(): Collection
    {
        $this->loadRolesAndPermissionsRelations();

        return $this->groupRole->role->permissions ?? collect([]);
    }

    private function loadRolesAndPermissionsRelations(): void
    {
        $this->load('groupRole.role.permissions');
    }
}
