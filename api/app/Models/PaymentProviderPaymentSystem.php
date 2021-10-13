<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProviderPaymentSystem extends Model
{
    public $timestamps = false;
    protected $table = 'payment_provider_payment_system';

    protected $fillable = [
        'payment_provider_id', 'payment_system_id'
    ];


}
