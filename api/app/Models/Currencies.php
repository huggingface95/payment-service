<?php

namespace App\Models;


class Currencies extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'minor_unit'
    ];

    public $timestamps = false;

}
