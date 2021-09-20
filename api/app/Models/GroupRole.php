<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    public $timestamps = false;
    protected $table = 'group_role';

//    public function role()
//    {
//        return $this->belongsTo(Roles::class,"role_id");
//    }
}
