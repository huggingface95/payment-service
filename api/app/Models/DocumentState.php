<?php

namespace App\Models;

class DocumentState extends BaseModel
{
    public $timestamps = false;
    
    protected $fillable = [
        'name',
    ];
}
