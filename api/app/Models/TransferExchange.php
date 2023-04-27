<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
        'client_type',
        'user_type',
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function debitedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debited_account_id', 'id');
    }

    public function creditedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credited_account_id', 'id');
    }

    public function clientable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'requested_by_id');
    }

    public function client(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'client_type', 'client_id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }

    public function quoteProviders(): HasManyThrough
    {
        return $this->hasManyThrough(
            QuoteProvider::class,
            Company::class,
            'id',
            'company_id',
            'company_id',
            'id'
        );
    }

}
