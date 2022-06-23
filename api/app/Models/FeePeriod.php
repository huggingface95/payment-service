<?php

namespace App\Models;

class FeePeriod extends BaseModel
{
    protected $table = 'fee_period';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
