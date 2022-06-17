<?php

namespace App\Models;


class ClientIpAddress extends BaseModel
{

    protected $table = "client_ip_address";
    protected $fillable = [
        'ip_address', 'client_type', 'client_id'
    ];

    public $timestamps = false;


}
