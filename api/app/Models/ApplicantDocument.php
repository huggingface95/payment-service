<?php

namespace App\Models;

use App\Events\Applicant\ApplicantDocumentCreatedEvent;
use App\Events\Applicant\ApplicantDocumentUpdatedEvent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicantDocument extends BaseModel
{
    protected $dispatchesEvents = [
        'created' => ApplicantDocumentCreatedEvent::class,
        'updated' => ApplicantDocumentUpdatedEvent::class,
    ];

    protected $fillable = [
        'added_from',
        'country_id',
        'document_type_id',
        'document_state_id',
        'file_id',
        'applicant_id',
        'applicant_type',
        'company_id',
        'info',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'file_id');
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(ApplicantDocumentInternalNote::class, 'applicant_document_id');
    }
}
