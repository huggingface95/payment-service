<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionsList extends Model
{
    protected $table="permissions_list";

    protected $fillable = [
        'name','type','permission_group_id'
    ];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permissions::class,"permission_list_id");
    }

}
