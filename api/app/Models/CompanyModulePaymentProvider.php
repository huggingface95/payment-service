<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

}
