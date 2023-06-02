<?php

namespace App\Models;

class Employee extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'employees_number',
    ];
}
