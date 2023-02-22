<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicantDocumentRejectDetail extends BaseModel
{
    protected $fillable = [
        'applicant_document_id',
        'member_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function applicantDocument(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocument::class, 'applicant_document_id');
    }

    public function applicantDocumentTags(): BelongsToMany
    {
        return $this->belongsToMany(
            ApplicantDocumentTag::class,
            'applicant_document_reject_detail_relation',
            'applicant_document_reject_detail_id',
            'applicant_document_tag_id'
        );
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }
}
