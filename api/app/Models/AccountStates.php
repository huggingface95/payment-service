<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountStates extends Model
{
    const WAITING_IBAN_ACTIVATION = 6;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;


}
