<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class,'group_role','group_type_id','role_id');
    }

}
