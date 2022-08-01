<?php

namespace App\Models;

/**
 * @property string $name
 */
class OperationType extends BaseModel
{
    protected $table = 'operation_type';

    public const INCOMING_TRANSFER = 'Incoming Transfer';

    public const OUTGOING_TRANSFER = 'Outgoing Transfer';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
