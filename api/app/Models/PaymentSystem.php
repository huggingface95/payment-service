<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSystem extends Model
{

    public $timestamps = false;

    protected $table="payment_system";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active'
    ];


}
