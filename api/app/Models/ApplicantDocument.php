<?php

namespace App\Models;

use App\Events\Applicant\ApplicantDocumentCreatedEvent;
use App\Events\Applicant\ApplicantDocumentUpdatedEvent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function documentState(): BelongsTo
    {
        return $this->belongsTo(DocumentState::class, 'document_state_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(ApplicantDocumentInternalNote::class, 'applicant_document_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ApplicantDocumentTag::class,
            'applicant_document_tag_relation',
            'applicant_document_id',
            'applicant_document_tag_id'
        );
    }
}
