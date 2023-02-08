<?php

namespace App\Models;

class DocumentType extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
