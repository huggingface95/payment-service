<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;

class ApplicantIndividualModules extends BaseModel
{
    protected $table = 'applicant_individual_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id', 'applicant_module_id', 'is_active',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    /**
     * Get relation applicant_individual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'id');
    }

    /**
     * Get relation applicant_modules
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(ApplicantModules::class, 'applicant_module_id', 'id');
    }
}
