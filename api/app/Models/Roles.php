<?php

namespace App\Models;

use App\Enums\GuardEnum;
use Spatie\Permission\Models\Role as SpatieRole;

class Roles extends SpatieRole
{
    protected $fillable = [
        'name', 'guard_name', 'description'
    ];

    protected $guard_name = GuardEnum::GUARD_NAME;

    public function groups()
    {
        return $this->belongsToMany(Groups::class,'group_role','group_id','role_id');
    }

    public function scopeGroupsSort($query, $sort)
    {
        return $query->with('groups')->orderBy('id',$sort);
    }

}
