<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KycTimeline extends BaseModel
{
    public $table = 'kyc_timeline';

    public $timestamps = false;

    protected $fillable = [
        'creator_id',
        'os',
        'browser',
        'ip',
        'action',
        'tag',
        'action_type',
        'document_id',
        'company_id',
        'applicant_id',
        'applicant_type',
        'action_old_value',
        'action_new_value',
        'created_at',
    ];

    protected $casts = [
        'action_old_value' => 'array',
        'action_new_value' => 'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function clientable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'applicant_type', 'applicant_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocument::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'creator_id', 'id');
    }
}
