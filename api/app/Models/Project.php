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
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'avatar_id')->where('entity_type', 'project');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(ApplicantModules::class, 'module_id');
    }

    public function projectSettings(): HasMany
    {
        return $this->hasMany(ProjectSettings::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

}
