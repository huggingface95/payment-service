<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApplicantIndividualCompany extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'applicant_individual_company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_id',
        'applicant_type',
        'applicant_company_id',
        'applicant_individual_company_relation_id',
        'applicant_individual_company_position_id',
        'percentage_owned',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function ApplicantIndividual(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'id');
    }

    public function ApplicantCompany(): BelongsTo
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id');
    }

    public function ApplicantIndividualCompanyRelation(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompanyRelation::class, 'applicant_individual_company_relation_id', 'id');
    }

    public function ApplicantIndividualCompanyPosition(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompanyPosition::class, 'applicant_individual_company_position_id', 'id');
    }

    public function ApplicantIndividualState()
    {
        return $this->belongsTo(ApplicantStatus::class, 'applicant_individual_status_id', 'id');
    }

    public function clientable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'applicant_type', 'applicant_id');
    }
}
