<?php

namespace App\Models;

class ApplicantLabels extends BaseModel
{

    protected $table="applicant_individual_label_relation";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id','applicant_individual_label_id'
    ];
    public $timestamps = false;

    /**
     * Get relation applicant_individual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id','applicant_individual_label_id', 'id');
    }

    /**
     * Get relation applicant_modules
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function ApplicantIndividualLabel()
    {
        return $this->belongsTo(ApplicantIndividualLabel::class,'applicant_individual_label_id','id');
    }

    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
    }

}
