<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantDocumentTag extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocumentTagCategory::class, 'category_id');
    }
}
