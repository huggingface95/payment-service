<?php

namespace App\Models;

class AccountState extends BaseModel
{
    const WAITING_FOR_APPROVAL = 1;

    const WAITING_FOR_ACCOUNT_GENERATION = 2;

    const AWAITING_ACCOUNT = 3;

    const ACTIVE = 4;

    const CLOSED = 5;

    const SUSPENDED = 6;

    const REJECTED = 7;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
