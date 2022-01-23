<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualModules extends Model
{

    protected $table="applicant_individual_modules";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id','applicant_module_id'
    ];
    public $timestamps = false;

    /**
     * Get relation applicant_individual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id','id');
    }

    /**
     * Get relation applicant_modules
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantModules()
    {
        return $this->belongsTo(ApplicantModules::class,'applicant_module_id','id');
    }

}