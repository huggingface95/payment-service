<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TransferIncomingHistory extends BaseModel
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
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public static function booted(): void
    {
        parent::booted();

        static::creating(function ($model) {
            $model->created_at = Carbon::now();
        });
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }
}
