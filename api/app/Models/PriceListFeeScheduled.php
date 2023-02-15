<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListFeeScheduled extends BaseModel
{
    protected $table = 'price_list_fee_scheduled';

    public $timestamps = false;

    protected $fillable = [
        'price_list_fee_id',
        'starting_date',
        'end_date',
        'executed_date',
        'recurrent_interval',
        'starting_date_id',
        'end_date_id',
    ];

    protected $casts = [
        'starting_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'end_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function priceListFee(): BelongsTo
    {
        return $this->belongsTo(PriceListFee::class);
    }
}
