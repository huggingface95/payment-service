<?php

namespace App\Models;

class EmailVerification extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'type',
        'token',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];
}
