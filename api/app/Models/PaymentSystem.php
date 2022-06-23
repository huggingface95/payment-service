<?php

namespace App\Models;

class PaymentSystem extends BaseModel
{
    public $timestamps = false;

    protected $table = 'payment_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active',
    ];
}
