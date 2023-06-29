<?php

namespace App\Models;

use App\Models\Builders\TransferBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class TransferBetweenAccount
 */
class Transfer extends BaseModel
{
    protected $table = 'transfers_view';

    protected static function booted()
    {
        parent::booted();
    }
    public function newEloquentBuilder($builder): TransferBuilder
    {
        return new TransferBuilder($builder);
    }


    public function transfer(): MorphTo
    {
        return $this->morphTo('transferable', 'transfer_type', 'transfer_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function paymentOperation(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id', 'id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id', 'id');
    }

    public function transferType(): BelongsTo
    {
        return $this->belongsTo(TransferType::class,  'transfer_type_id', 'id');
    }

}
