<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantLabels extends BaseModel
{
    protected $table = 'applicant_individual_label_relation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id', 'applicant_individual_label_id',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    /**
     * Get relation applicant_individual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'applicant_individual_label_id', 'id');
    }

    public function ApplicantIndividualLabel(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualLabel::class, 'applicant_individual_label_id', 'id');
    }

    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
    }
}
