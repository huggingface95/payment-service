<?php

namespace App\Models;

use App\Enums\GuardEnum;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role;

class Permissions extends SpatiePermission
{

    const TYPE_ADD = 'add';
    const TYPE_INFO = 'info';
    const TYPE_EXPORT = 'export';
    const TYPE_EDIT = 'edit';
    const TYPE_IMPORTANT = 'important';
    const TYPE_READ = 'read';
    const TYPE_REQUIRED = 'required';
    const TYPE_NO_REQUIRED = 'no_required';

    const ACTION_TYPE_VIEW = 'view';
    const ACTION_TYPE_UPDATE = 'update';
    const ACTION_TYPE_CREATE = 'create';
    const ACTION_TYPE_DELETE = 'delete';
    const ACTION_TYPE_RESTORE = 'restore';
    const ACTION_TYPE_FORCE_DELETE = 'forceDelete';
    const ACTION_TYPE_EXPORT = 'export';
    const ACTION_TYPE_AVAILABLE = 'available';
    const ACTION_TYPE_ATTACH = 'attach';
    const ACTION_TYPE_DETACH = 'detach';


    protected $fillable = [
        'name', 'guard_name', 'display_name', 'type', 'permission_list_id', 'action_type', 'model'
    ];
    protected $guard_name = GuardEnum::GUARD_NAME;

    /**
     * @return bool
     */
    public static function getActionTypes(): array
    {
        return [
            self::ACTION_TYPE_VIEW,
            self::ACTION_TYPE_UPDATE,
            self::ACTION_TYPE_CREATE,
            self::ACTION_TYPE_DELETE,
            self::ACTION_TYPE_RESTORE,
            self::ACTION_TYPE_FORCE_DELETE,
            self::ACTION_TYPE_EXPORT,
            self::ACTION_TYPE_AVAILABLE,
            self::ACTION_TYPE_ATTACH,
            self::ACTION_TYPE_DETACH,
        ];
    }

    public static function getTreePermissions($roleId = null)
    {
        if ($roleId) {
            $role = Role::find($roleId);
            $permissions = $role->permissions;
        } else {
            $permissions = self::orderBy('id', 'asc')->get();
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
            $current['permissions'][] = ['permission_id' => $item->id, 'permission_name' => $permission];
            $permData[$name]['rules'] = $out;
        }
        return $permData;
    }


    public static function getPermissionArrayNamesById(array $permissionId)
    {
        return array_column(self::select('name')->whereIn('id', $permissionId)->get()->toArray(), 'name');
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name'], 'type' => $attributes['type']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

}
