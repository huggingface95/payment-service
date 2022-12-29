<?php

namespace App\Models;

class BankCorrespondentCurrency extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_correspondent_id',
        'currency_id',
    ];

    public $timestamps = false;
}
