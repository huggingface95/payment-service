<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicantDocumentInternalNote extends BaseModel
{
    protected $fillable = [
        'applicant_document_id',
        'member_id',
        'note',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocument::class, 'applicant_document_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ApplicantDocumentTag::class,
            'applicant_document_internal_note_tag_relation',
            'applicant_document_internal_note_id',
            'applicant_document_tag_id'
        );
    }
}
