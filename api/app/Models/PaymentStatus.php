<?php

namespace App\Models;

class PaymentStatus extends BaseModel
{
    const PENDING_ID = 1;

    const COMPLETED_ID = 2;

    protected $table = 'payment_status';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
