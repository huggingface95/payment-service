<?php

namespace App\Models;


/**
 * @property string $name
 */
class OperationType extends BaseModel
{
    protected $table="operation_type";

    const INCOMING_TRANSFER  = "Incoming Transfer";
    const OUTGOING_TRANSFER = "Outgoing Transfer";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
