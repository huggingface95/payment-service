<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferBetween extends BaseModel
{
    protected $table = 'transfer_between_relation';

    public $timestamps = false;

    protected $fillable = [
        'transfer_outgoing_id',
        'transfer_incoming_id',
    ];

    public function transferOutgoing(): BelongsTo
    {
        return $this->belongsTo(TransferOutgoing::class, 'transfer_outgoing_id');
    }

    public function transferOutgoingHistory(): HasMany
    {
        return $this->hasMany(TransferOutgoingHistory::class, 'transfer_id', 'transfer_outgoing_id');
    }

    public function transferIncoming(): BelongsTo
    {
        return $this->belongsTo(TransferIncoming::class, 'transfer_incoming_id');
    }

    public function transferIncomingHistory(): HasMany
    {
        return $this->hasMany(TransferIncomingHistory::class, 'transfer_id', 'transfer_incoming_id');
    }
}
