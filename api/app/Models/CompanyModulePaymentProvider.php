<?php

namespace App\Models;

class CompanyModulePaymentProvider extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'company_module_id',
        'payment_provider_id',
        'wallet',
        'api_key',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];
}
