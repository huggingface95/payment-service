<?php

namespace App\Models;

class ApplicantIndividualNotes extends BaseModel
{
    protected $table = 'applicant_individual_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note', 'applicant_individual_id', 'member_id',
    ];

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
        return $this->belongsTo(ApplicantIndividual::class, ' applicant_individual_id');
    }
}
