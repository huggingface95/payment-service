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
        'first_name', 'last_name','email','sex','is_active','company_id','country_id','language_id','member_group_role_id','two_factor_auth_setting_id','password_hash','password_salt','last_login_at'
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

    public function role()
    {
        return $this->belongsTo(Roles::class,'member_group_role_id');
    }

    public function position()
    {
        return $this->belongsTo(DepartmentPosition::class,'department_position_id');
    }

}
