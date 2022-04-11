<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;


    public function groups()
    {
        return $this->hasMany(GroupRole::class,'group_type_id','id');
    }

}
