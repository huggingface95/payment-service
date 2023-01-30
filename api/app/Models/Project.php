<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    protected $casts = [
        'additional_fields_basic' => 'array',
        'additional_fields_settings' => 'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
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

    public function applicantCompanies(): HasMany
    {
        return $this->hasMany(ApplicantCompany::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

}
