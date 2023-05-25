<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceListQpFeeCurrency extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'price_list_qp_fee_currency';

    protected $fillable = [
        'price_list_qp_fee_id',
        'currency_id',
        'fee',
    ];

    public $timestamps = false;

    protected $casts = [
        'fee' => AsCollection::class,
    ];

    public function PriceListQpFee(): BelongsTo
    {
        return $this->belongsTo(PriceListQpFee::class, 'price_list_qp_fee_id', 'id');
    }

    public function feeDestinationCurrency(): HasMany
    {
        return $this->hasMany(PriceListQpFeeDestinationCurrency::class, 'price_list_qp_fee_currency_id');
    }
}
