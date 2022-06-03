<?php

namespace App\Models;


class TwoFactorAuthSettings extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}
