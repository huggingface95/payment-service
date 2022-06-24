<?php

namespace App\Models;

class GroupRoleUser extends BaseModel
{
    protected $table = 'group_role_members_individuals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_role_id', 'user_id',
    ];

    protected $primaryKey = 'user_id';

    public $timestamps = false;
}
