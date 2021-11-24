<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualRiskLevel extends Model
{

    protected $table="applicant_individual_risk_level";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description','applicant_individual_id','member_id'
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
