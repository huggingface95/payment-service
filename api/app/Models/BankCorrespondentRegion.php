<?php

namespace App\Models;

class BankCorrespondentRegion extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_correspondent_id',
        'region_id',
    ];

    public $timestamps = false;

}
