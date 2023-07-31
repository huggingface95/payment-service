<?php

namespace App\Models;

use App\Events\Applicant\ApplicantIndividualNoteCreatedEvent;
use App\Models\Scopes\ApplicantIndividualCompanyIdScope;
use App\Models\Traits\BaseObServerTrait;

class ApplicantIndividualNotes extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'applicant_individual_notes';

    protected $dispatchesEvents = [
        'created' => ApplicantIndividualNoteCreatedEvent::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note', 'applicant_individual_id', 'member_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantIndividualCompanyIdScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id');
    }
}
