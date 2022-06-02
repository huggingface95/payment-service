<?php

namespace App\Models;


class PaymentTypes extends BaseModel
{
    const INCOMING = 'Incoming';
    const OUTGOING = 'Outgoing';
    const FEE = 'Fee';
    const BETWEEN_ACCOUNT = 'Between Account';

    protected $table="payment_types";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
