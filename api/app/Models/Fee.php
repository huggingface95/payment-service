<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Fee
 */
class Fee extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'fee',
        'fee_pp',
        'fee_type_id',
        'transfer_id',
        'operation_type_id',
        'member_id',
        'status_id',
        'client_id',
        'account_id',
        'price_list_fee_id',
        'transfer_type',
    ];

    public function transferOutgoing(): BelongsTo
    {
        return $this->belongsTo(TransferOutgoing::class, 'transfer_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }
}
