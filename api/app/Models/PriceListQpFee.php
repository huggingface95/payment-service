<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use App\Services\PriceListFeeService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PriceListQpFee
 */
class PriceListQpFee extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'price_list_qp_fees';

    protected $fillable = [
        'name',
        'type_id',
        'operation_type_id',
        'period_id',
        'quote_provider_id',
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

    public function fees(): HasMany
    {
        return $this->hasMany(PriceListQpFeeCurrency::class, 'price_list_qp_fee_id');
    }
}
