<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantDocument extends BaseModel
{
    protected $fillable = [
        'document_type_id',
        'document_state_id',
        'file_id',
        'applicant_id',
        'applicant_type',
        'company_id',
        'info',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'file_id');
    }
}
