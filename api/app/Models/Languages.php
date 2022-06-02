<?php

namespace App\Models;


class Languages extends BaseModel
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
