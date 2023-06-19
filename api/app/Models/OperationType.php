<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $name
 */
class OperationType extends BaseModel
{
    public $timestamps = false;

    protected $table = 'operation_type';

    protected $fillable = [
        'name',
        'fee_type_id',
        'transfer_type_id',
    ];

    public function transferType(): BelongsTo
    {
        return $this->belongsTo(TransferType::class);
    }

    public function paymentSystems(): BelongsToMany
    {
        return $this->belongsToMany(PaymentSystem::class, 'payment_system_operation_types', 'operation_type_id', 'payment_system_id');
    }
}
