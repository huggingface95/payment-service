<?php

namespace App\Models;

class State extends BaseModel
{
    public const INACTIVE = 1;

    public const ACTIVE = 2;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
