<?php

namespace App\Models;

class TransferType extends BaseModel
{
    protected $table = 'transfer_types';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public const INCOMING = 'Incoming Transfer';

    public const OUTGOING = 'Outgoing Transfer';
}
