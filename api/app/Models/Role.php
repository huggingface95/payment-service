<?php

namespace App\Models;

use App\Enums\GuardEnum;
use App\Models\Scopes\OrderByLowerScope;
use App\Models\Scopes\RoleFilterSuperAdminScope;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

/**
 * Class Role
 *
 * @property int id
 * @property Collection $permissions
 */
class Role extends SpatieRole
{
    public const SUPER_ADMIN_ID = 35;

    protected $fillable = [
        'name', 'guard_name', 'description', 'company_id', 'group_type_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    protected $guard_name = GuardEnum::GUARD_NAME;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new OrderByLowerScope());
        static::addGlobalScope(new RoleFilterSuperAdminScope());
    }

    public function IsSuperAdmin(): bool
    {
        return $this->id == self::SUPER_ADMIN_ID;
    }

    public function groupType()
    {
        return $this->belongsTo(GroupType::class, 'group_type_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(GroupRole::class, 'role_id');
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class,
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }

    public function permissionCategories()
    {
        return $this->belongsToMany(PermissionCategory::class, 'permission_category_role', 'role_id', 'permission_category_id');
    }

    /**
     * @param $query
     * @param $sort
     * @return mixed
     */
    public function scopeGroupsSort($query, $sort)
    {
        return $query->with('groups')->orderBy('id', $sort);
    }

    /**
     * @return int
     */
    public function getGroupsCountAttribute(): int
    {
        return $this->groups()->count();
    }

    public function getPermissionsTreeAttribute(): array
    {
        return Permissions::getTreePermissions();
    }

    public function getGroupsIdByRole(): array
    {
        $ids = [];
        foreach ($this->groups as $group) {
            $ids[] = $group->id;
        }

        return $ids;
    }
}
