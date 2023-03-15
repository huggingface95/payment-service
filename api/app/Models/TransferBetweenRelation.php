<?php

namespace App\Models;

class TransferBetweenRelation extends BaseModel
{
    protected $table = 'transfer_between_relation';

    public $timestamps = false;

    protected $fillable = [
        'transfer_outgoing_id',
        'transfer_incoming_id',
    ];
}
