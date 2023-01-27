<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ApplicantDocumentTag extends BaseModel
{

    protected $fillable = [
        'category_id',
        'name',
        'member_id',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ApplicantDocumentTagCategory::class, 'category_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function setMemberIdAttribute($value)
    {
        $this->attributes['member_id'] = Auth::user()->id;
    }
}
