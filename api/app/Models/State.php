<?php

namespace App\Models;

class State extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
