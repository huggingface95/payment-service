<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    protected $table="permission_category";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function permissionsList()
    {
        return $this->hasMany(PermissionsList::class,"permission_group_id");
    }

}
