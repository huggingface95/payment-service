<?php

namespace App\Models;

use App\Events\Applicant\ApplicantCompanyNoteCreatedEvent;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantCompanyNotes extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'applicant_company_notes';

    protected $dispatchesEvents = [
        'created' => ApplicantCompanyNoteCreatedEvent::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note', 'applicant_company_id', 'member_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function applicantCompany(): BelongsTo
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id');
    }

    public function applicantIndividualCompany(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompany::class, 'applicant_company_id', 'applicant_company_id');
    }
}
