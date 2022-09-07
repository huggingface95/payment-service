<?php

namespace App\Models;

/**
 * Class AccountState
 *
 * @property string name
 *
 */
class AccountState extends BaseModel
{
    public const WAITING_FOR_APPROVAL = 1;

    public const WAITING_FOR_ACCOUNT_GENERATION = 2;

    public const AWAITING_ACCOUNT = 3;

    public const ACTIVE = 4;

    public const CLOSED = 5;

    public const SUSPENDED = 6;

    public const REJECTED = 7;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
