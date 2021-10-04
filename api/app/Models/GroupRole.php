<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    public $timestamps = false;
    protected $table = 'group_role';

    protected $fillable = [
        'group_id', 'role_id'
    ];

    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }

}
