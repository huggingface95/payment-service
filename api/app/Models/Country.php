<?php

namespace App\Models;


class Country extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'iso'
    ];

    public $timestamps = false;

}
