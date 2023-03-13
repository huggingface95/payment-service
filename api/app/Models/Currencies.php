<?php

namespace App\Models;

/**
 * @property string name
 * @property string code
 */
class Currencies extends BaseModel
{
    public const ALL_CURRENCIES = 180;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'minor_unit',
    ];

    public $timestamps = false;
}
