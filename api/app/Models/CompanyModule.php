<?php

namespace App\Models;

use App\Models\Scopes\PivotModuleScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyModule extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'module_id',
        'is_active',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PivotModuleScope);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function paymentProviders(): HasMany
    {
        return $this->hasMany(CompanyModulePaymentProvider::class, 'company_module_id', 'id');
    }

    public function ibanProviders(): HasMany
    {
        return $this->hasMany(CompanyModuleIbanProvider::class, 'company_module_id', 'id');
    }
}
