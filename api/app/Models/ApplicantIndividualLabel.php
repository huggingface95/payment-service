<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicantIndividualLabel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const DEFAULT_MEMBER_ID = 2;

    protected $table="applicant_individual_labels";

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

    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_individual_label_relation','applicant_individual_label_id','applicant_individual_id');
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
