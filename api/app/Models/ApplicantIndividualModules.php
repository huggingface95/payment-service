<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicantIndividualModules extends BaseModel
{
    protected $table = 'applicant_individual_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id',
        'applicant_module_id',
        'is_active',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    public function ApplicantIndividual(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'applicant_individual_modules', 'applicant_module_id', 'applicant_individual_id');
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
