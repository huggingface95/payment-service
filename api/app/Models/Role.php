<?php

namespace App\Models;

use App\Enums\GuardEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name', 'guard_name', 'description'
    ];

    protected $guard_name = GuardEnum::GUARD_NAME;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupTypes(): BelongsToMany
    {
        return $this->belongsToMany(Groups::class,'group_role','role_id','group_type_id');
    }

    public function groups()
    {
        return $this->hasMany(GroupRole::class, 'role_id');
    }

    /**
     * @param $query
     * @param $sort
     * @return mixed
     */
    public function scopeGroupsSort($query, $sort)
    {
        return $query->with('groups')->orderBy('id',$sort);
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
        foreach ($this->groups as $group)
        {
            $ids[] = $group->id;
        }
        return $ids;
    }

}
