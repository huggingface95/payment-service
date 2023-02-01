<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
