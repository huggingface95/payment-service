<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferBetweenRelation extends BaseModel
{
    protected $table = 'transfer_between_relation';

    public $timestamps = false;

    protected $fillable = [
        'transfer_outgoing_id',
        'transfer_incoming_id',
    ];
}
