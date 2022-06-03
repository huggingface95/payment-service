<?php

namespace App\Models;


class FeeType extends BaseModel
{
    protected $table="fee_types";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
