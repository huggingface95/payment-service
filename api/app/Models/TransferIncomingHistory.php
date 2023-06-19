<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class TransferIncomingHistory extends BaseModel
{
    use BaseObServerTrait;

    public $timestamps = false;

    protected $fillable = [
        'transfer_id',
        'status_id',
        'action',
        'comment',
        'created_at',
        'managed_id',
        'managed_type',
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

    public function managed(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'managed_type', 'managed_id');
    }
}
