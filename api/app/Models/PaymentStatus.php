<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    const PENDING_ID = 1;
    const COMPLETED_ID = 2;

    protected $table="payment_status";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
