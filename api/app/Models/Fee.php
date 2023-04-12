<?php

namespace App\Models;

use App\Models\Scopes\AccountIndividualsCompaniesScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Fee
 */
class Fee extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'fee',
        'fee_type_id',
        'transfer_id',
        'operation_type_id',
        'member_id',
        'status_id',
        'client_id',
        'client_type',
        'account_id',
        'price_list_fee_id',
        'transfer_type',
        'fee_type_mode_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function getFeeAmountAttribute()
    {
        return $this->fees()->sum('fee');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(self::class, 'transfer_id', 'transfer_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->withoutGlobalScope(AccountIndividualsCompaniesScope::class);
    }

    public function client(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'client_type', 'client_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function operationType(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }

    public function priceListFee(): BelongsTo
    {
        return $this->belongsTo(PriceListFee::class, 'price_list_fee_id');
    }

    public function transferOutgoing(): BelongsTo
    {
        return $this->belongsTo(TransferOutgoing::class, 'transfer_id');
    }

    public function mode(): BelongsTo
    {
        return $this->belongsTo(FeeMode::class, 'fee_type_mode_id');
    }
}
