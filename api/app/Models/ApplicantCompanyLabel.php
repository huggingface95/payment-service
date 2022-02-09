<?php

namespace App\Models;


class ApplicantCompanyLabel extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hex_color_code', 'member_id'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_id', function ($builder) {
            $memberId = self::DEFAULT_MEMBER_ID;
            $companyId = Members::where('id', '=', $memberId)->value('company_id');
            $companyMembers = Members::where('company_id', '=', $companyId)->get('id');
            $result = collect($companyMembers)->pluck('id')->toArray();
            return $builder->whereIn('member_id', $result);
        });
    }

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_company_label_relation','applicant_company_label_id','applicant_company_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    public function ApplicantCompanyLabels()
    {
        return $this->belongsToMany(ApplicantCompanyLabel::class,'applicant_company_label_relation','applicant_company_id', 'applicant_company_label_id');
    }


    public function scopeCompanyId($query, $id)
    {
        $company = ApplicantCompany::where('id', '=', $id)->first();
        $labels = collect($company->labels()->get())->pluck('id')->toArray();
        return $query->whereNotIn('id', $labels);
    }

}
