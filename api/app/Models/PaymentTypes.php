<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTypes extends Model
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
