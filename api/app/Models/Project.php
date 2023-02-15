<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


/**
 * Class Project
 *
 * @property Company $company
 */
class Project extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'description',
        'client_url',
        'support_email',
        'login_url',
        'sms_sender_name',
        'company_id',
        'module_id',
        'avatar_id',
        'state_id',
        'additional_fields_basic',
        'additional_fields_settings',
        'forgot_password_url',
    ];

    protected $casts = [
        'additional_fields_basic' => 'array',
        'additional_fields_settings' => 'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'avatar_id')->where('entity_type', 'project');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function projectSettings(): HasMany
    {
        return $this->hasMany(ProjectSettings::class);
    }

    public function projectApiSettings(): HasMany
    {
        return $this->hasMany(ProjectApiSetting::class);
    }

    public function paymentProviders(): MorphToMany
    {
        return $this->morphedByMany(PaymentProvider::class, 'provider', ProjectApiSetting::class);
    }

    public function paymentProvidersIban(): MorphToMany
    {
        return $this->morphedByMany(PaymentProviderIban::class, 'provider', ProjectApiSetting::class);
    }

    public function applicantCompanies(): HasMany
    {
        return $this->hasMany(ApplicantCompany::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
