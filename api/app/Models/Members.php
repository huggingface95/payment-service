<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected $fillable = [
        'first_name', 'last_name','email','sex','is_active','company_id','country_id','language_id','member_group_role_id','two_factor_auth_setting_id','password_hash','password_salt'
    ];

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
}
