<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Members extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use SoftDeletes, Authorizable, Authenticatable;

    public $password_confirmation;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'sex', 'is_active', 'company_id', 'country_id', 'language_id', 'member_group_role_id', 'two_factor_auth_setting_id', 'password_hash', 'password_salt', 'last_login_at', 'additional_fields', 'additional_info_fields'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $dates = ['deleted_at'];


    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(DepartmentPosition::class, 'department_position_id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'member_group_role_id');
    }

    public function getGroupAttribute()
    {
        return $this->groupRole()->join('groups', 'groups.id', '=', 'group_role.group_id')->select('groups.*')->first();
    }

    public function getRoleAttribute()
    {
        return $this->groupRole()->join('roles', 'roles.id', '=', 'group_role.role_id')->select('roles.*')->first();
    }

    public function getDepartmentAttribute()
    {
        return $this->position()
            ->join('departments', 'departments.id', '=', 'department_position.department_id')->select('departments.*')->first();
    }

    public function groupRoles(): BelongsToMany
    {
        return $this->belongsToMany(GroupRole::class, 'group_role_member', 'member_id', 'group_role_id');
    }

}
