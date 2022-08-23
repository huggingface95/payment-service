<?php

namespace App\Models;

use App\Enums\GuardEnum;
use App\Models\Scopes\PermissionFilterSuperAdminScope;
use App\Models\Scopes\PermissionOrderScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role;

/**
 * @method static firstOrCreate(mixed $data)
 *
 *@property int $id
 *@property boolean is_super_admin
 */
class Permissions extends SpatiePermission
{
    public const TYPE_ADD = 'add';

    public const TYPE_INFO = 'info';

    public const TYPE_EXPORT = 'export';

    public const TYPE_EDIT = 'edit';

    public const TYPE_IMPORTANT = 'important';

    public const TYPE_READ = 'read';

    public const TYPE_REQUIRED = 'required';

    public const TYPE_NO_REQUIRED = 'no_required';

    protected $fillable = [
        'name', 'guard_name', 'display_name', 'type', 'permission_list_id', 'order', 'is_super_admin',
    ];

    protected $guard_name = GuardEnum::GUARD_NAME;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PermissionOrderScope());
        static::addGlobalScope(new PermissionFilterSuperAdminScope());
    }

    public function permissionList(): BelongsTo
    {
        return $this->belongsTo(PermissionsList::class, 'permission_list_id');
    }

    public static function getTreePermissions($roleId = null): array
    {
        if ($roleId) {
            $role = Role::find($roleId);
            $permissions = $role->permissions;
        } else {
            $permissions = self::query()->get();
        }

        $permData = [];
        foreach ($permissions ?? [] as $item) {
            $actions = explode('.', $item->name);
            $name = array_shift($actions);
            $permission = array_pop($actions);
            $current = &$out;
            foreach ($actions as $level) {
                if (! isset($current[$level])) {
                    $current[$level] = [];
                }
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

        $permission = static::getPermission([
            'name' => $attributes['name'],
            'guard_name' => $attributes['guard_name'],
            'type' => $attributes['type'],
            'permission_list_id'=>$attributes['permission_list_id'],
        ]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }
}
