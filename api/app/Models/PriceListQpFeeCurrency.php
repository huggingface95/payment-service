<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListQpFeeCurrency extends BaseModel
{
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
}
