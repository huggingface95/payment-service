<?php

namespace App\Models;

class PaymentBankRegion extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_bank_id',
        'region_id',
    ];

    public $timestamps = false;
}
