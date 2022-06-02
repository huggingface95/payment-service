<?php

namespace App\Models;


class OperationType extends BaseModel
{
    protected $table="operation_type";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
