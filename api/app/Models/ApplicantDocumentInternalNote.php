<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantDocumentInternalNote extends BaseModel
{
    protected $fillable = [
        'applicant_document_id',
        'member_id',
        'note',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocument::class, 'applicant_document_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }
}
