<?php

namespace App\Models;

use App\Enums\GuardEnum;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role;

class Permissions extends SpatiePermission
{
    protected $fillable = [
        'name', 'guard_name','display_name'
    ];
    protected $guard_name = GuardEnum::GUARD_NAME;

    public static function getTreePermissions($roleId = null)
    {
        if ($roleId) {
            $role = Role::find($roleId);
            $permissions = $role->permissions;
        } else {
            $permissions = self::orderBy('id','asc')->get();
        }

        $permData = [];

        $permData = [];
        foreach ($permissions ?? [] as $item) {
            $actions = explode('.', $item->name);
            $name = array_shift($actions);
            $permission = array_pop($actions);
            $current = &$out;
            foreach ($actions as $level) {
                if (!isset($current[$level]))
                    $current[$level] = array();
                $current = &$current[$level];
            }
            $current['permissions'][] = $permission;
            $permData[$name]['rules'] = $out;
        }
        return $permData;
    }

}
