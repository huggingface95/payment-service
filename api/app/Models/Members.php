<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Members extends Model
{
    use SoftDeletes;

    public $password_confirmation;

    protected $fillable = [
        'first_name', 'last_name','email','sex','is_active','company_id','country_id','language_id','member_group_role_id','two_factor_auth_setting_id','password_hash','password_salt','last_login_at','additional_fields'
    ];

    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo(Companies::class,'company_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function language()
    {
        return $this->belongsTo(Languages::class,'language_id');
    }

    public function position()
    {
        return $this->belongsTo(DepartmentPosition::class,'department_position_id');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentPosition::class, 'department_position_id')
            ->join('departments', 'departments.id', '=', 'department_position.department_id');
    }

    public function groupRole()
    {
        return $this->belongsTo(GroupRole::class,'member_group_role_id');
    }

    public function getGroupAttribute()
    {
        return $this->groupRole()->join('groups', 'groups.id', '=', 'group_role.group_id')->select('groups.*')->first();
    }

    public function getRoleAttribute()
    {
        return $this->groupRole()->join('roles', 'roles.id', '=', 'group_role.role_id')->select('roles.*')->first();
    }

}
