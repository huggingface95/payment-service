<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentUrgency extends Model
{
    protected $table="payment_urgency";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
