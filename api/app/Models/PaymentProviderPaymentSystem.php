<?php

namespace App\Models;


class PaymentProviderPaymentSystem extends BaseModel
{
    public $timestamps = false;
    protected $table = 'payment_provider_payment_system';

    protected $fillable = [
        'payment_provider_id', 'payment_system_id'
    ];


}
