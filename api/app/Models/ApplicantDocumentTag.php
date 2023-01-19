<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantDocumentTag extends BaseModel
{

    protected $fillable = [
        'category_id',
        'name',
        'member_id',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocumentTagCategory::class, 'category_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }
}
