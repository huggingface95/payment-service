<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyRateHistory extends BaseModel
{
    use BaseObServerTrait;

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'quote_provider_id',
        'currency_src_id',
        'currency_dst_id',
        'rate',
        'created_at',
    ];

    protected $casts = [
        'rate' => 'decimal:5',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];
}
