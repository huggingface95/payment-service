<?php

namespace App\Models;

class PaymentBankCurrency extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_bank_id',
        'currency_id',
    ];

    public $timestamps = false;
}
