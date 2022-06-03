<?php

namespace App\Models;


class PaymentUrgency extends BaseModel
{
    protected $table="payment_urgency";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
