<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyModuleIbanProvider extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'company_module_id',
        'payment_provider_iban_id',
        'wallet',
        'api_key',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    public function paymentIbanProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProviderIban::class, 'payment_provider_iban_id');
    }
}
