<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTypes extends Model
{
    protected $table="payment_types";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
