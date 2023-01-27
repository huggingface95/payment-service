<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyModulePaymentProvider extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'company_module_id',
        'payment_provider_id',
        'is_active',
    ];

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function projectApiSettings(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectApiSetting::class,
            ProjectSettings::class,
            'payment_provider_id',
            'project_id',
            'payment_provider_id',
            'project_id'
        );
    }

}
