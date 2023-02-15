<?php

namespace App\Models;

class PaymentProviderHistory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_provider_id',
        'transfer_id',
        'transfer_type',
        'provider_response',
    ];

    protected $casts = [
        'provider_response' => 'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];
}
