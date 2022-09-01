<?php

namespace App\Models;

class FeeMode extends BaseModel
{
    protected $table = 'fee_modes';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
