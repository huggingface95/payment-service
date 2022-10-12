<?php

namespace App\Models;

class EmailVerification extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'type',
        'token',
    ];
}
