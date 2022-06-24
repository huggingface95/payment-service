<?php

namespace App\Models;

class AccountState extends BaseModel
{
    const WAITING_IBAN_ACTIVATION = 6;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
