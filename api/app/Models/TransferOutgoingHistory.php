<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferOutgoingHistory extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'transfer_id',
        'status_id',
        'action',
        'comment',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }
}
