<?php

namespace App\Models;

class PaymentStatus extends BaseModel
{
    protected $table = 'payment_status';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
