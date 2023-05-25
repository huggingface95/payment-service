<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyModuleIbanProvider extends BaseModel
{
    use BaseObServerTrait;


    public $timestamps = false;

    protected $fillable = [
        'company_module_id',
        'payment_provider_iban_id',
        'is_active',
    ];

    public function paymentIbanProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProviderIban::class, 'payment_provider_iban_id');
    }

    public function projectApiSettings(): BelongsToMany
    {
        return $this->belongsToMany(
            ProjectApiSetting::class,
            ProjectSettings::class,
            'iban_provider_id',
            'project_id',
            'payment_provider_iban_id',
            'project_id'
        );
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            ProjectSettings::class,
            'iban_provider_id',
            'project_id',
            'payment_provider_iban_id',
            'id'
        );
    }
}
