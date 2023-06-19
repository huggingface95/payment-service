<?php

namespace App\Models;

use App\Models\Scopes\ApplicantIndividualCompanyIdScope;
use App\Models\Scopes\MemberScope;
use App\Models\Traits\BaseObServerTrait;

class ApplicantIndividualLabel extends BaseModel
{
    use BaseObServerTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'applicant_individual_labels';

    protected $fillable = [
        'name', 'hex_color_code', 'member_id',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MemberScope());
        static::addGlobalScope(new ApplicantIndividualCompanyIdScope());
    }

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'applicant_individual_label_relation', 'applicant_individual_label_id', 'applicant_individual_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function companyMembers()
    {
        return $this->hasMany(Members::class, 'company_id');
    }

    public function ApplicantIndividualLabel()
    {
        return $this->belongsTo(self::class, 'applicant_individual_label_id', 'id');
    }

    public function ApplicantIndividualLabels()
    {
        return $this->belongsToMany(self::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
    }

    public function scopeIndividualId($query, $id)
    {
        $applicant = ApplicantIndividual::where('id', '=', $id)->first();
        $labels = collect($applicant->labels()->get())->pluck('id')->toArray();

        return $query->whereNotIn('id', $labels);
    }
}
