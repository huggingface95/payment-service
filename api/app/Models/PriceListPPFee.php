<?php

namespace App\Models;

use App\Services\PriceListFeeService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PriceListPPFee
 */
class PriceListPPFee extends BaseModel
{
    protected $table = 'price_list_pp_fees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id',
        'operation_type_id',
        'period_id',
        'payment_system_id',
        'payment_provider_id',
        'company_id',
    ];

    protected $appends = [
        'fee_ranges',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function getFeeRangesAttribute(): array
    {
        $fees = $this->fees()->get();

        return (new PriceListFeeService())->convertFeesToFeeRanges($fees);
    }

    public function feePeriod(): BelongsTo
    {
        return $this->belongsTo(FeePeriod::class, 'period_id');
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class, 'type_id');
    }

    public function operationType(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PriceListPPFeeCurrency::class, 'price_list_pp_fee_id');
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, PriceListPPFeeCurrency::class, 'price_list_pp_fee_id', 'currency_id');
    }
}
