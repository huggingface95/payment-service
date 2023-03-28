<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferExchange extends BaseModel
{
    protected $fillable = [
        'company_id',
        'client_id',
        'requested_by_id',
        'debited_account_id',
        'credited_account_id',
        'status_id',
        'transfer_outgoing_id',
        'transfer_incoming_id',
        'exchange_rate',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function transferOutgoing(): BelongsTo
    {
        return $this->belongsTo(TransferOutgoing::class, 'transfer_outgoing_id');
    }

    public function transferIncoming(): BelongsTo
    {
        return $this->belongsTo(TransferIncoming::class, 'transfer_incoming_id');
    }
}
