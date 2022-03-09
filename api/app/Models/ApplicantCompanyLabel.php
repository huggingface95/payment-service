<?php

namespace App\Models;


use App\Models\Scopes\MemberScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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


    protected static function booted()
    {
        static::addGlobalScope(new MemberScope);
    }

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_company_label_relation','applicant_company_label_id','applicant_company_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }


    public function scopeIsActive(Builder $query, int $company_id = null): Builder
    {
        $query->select('applicant_company_labels.*',
            DB::raw('applicant_company_label_relation.applicant_company_id = '. $company_id .'  AS is_active'))
        ->leftJoin('applicant_company_label_relation','applicant_company_labels.id','=','applicant_company_label_relation.applicant_company_label_id');

        return $query;
    }

    public function scopeCompanyId($query, $id)
    {
        $company = ApplicantCompany::where('id', '=', $id)->first();
        $labels = collect($company->labels()->get())->pluck('id')->toArray();
        return $query->whereNotIn('id', $labels);
    }

}
