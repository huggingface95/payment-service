<?php

namespace App\Models;


use App\Models\Scopes\MemberScope;

class ApplicantIndividualLabel extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table="applicant_individual_labels";

    protected $fillable = [
        'name', 'hex_color_code', 'member_id'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new MemberScope);
    }

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_individual_label_relation','applicant_individual_label_id','applicant_individual_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    public function companyMembers()
    {
        return $this->hasMany(Members::class, 'company_id');
    }


    public function ApplicantIndividualLabel()
    {
        return $this->belongsTo(ApplicantIndividualLabel::class,'applicant_individual_label_id','id');
    }

    public function ApplicantIndividualLabels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class,'applicant_individual_label_relation','applicant_individual_id', 'applicant_individual_label_id');
    }

    public function scopeIndividualId($query, $id)
    {
        $applicant = ApplicantIndividual::where('id', '=', $id)->first();
        $labels = collect($applicant->labels()->get())->pluck('id')->toArray();
        return $query->whereNotIn('id', $labels);
    }

}
