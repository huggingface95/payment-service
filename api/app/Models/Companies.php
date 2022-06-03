<?php

namespace App\Models;


class Companies extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url','email','company_number','contact_name','country_id','zip','address','city', 'additional_fields'
    ];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }

    public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Languages','language_id');
    }

    public function members()
    {
        return $this->hasMany(Members::class,"company_id");
    }

    public function departments()
    {
        return $this->hasMany(Departments::class,"company_id");
    }

    public function positions()
    {
        return $this->hasMany(DepartmentPosition::class,"company_id");
    }

    public function companySettings()
    {
        return $this->hasOne(CompanySettings::class,'company_id','id');
    }

    public function scopeMemberSort($query, $sort)
    {
        return $query->withCount('members')->orderBy('members_count',$sort);
    }

    public function scopeCountrySort($query, $sort)
    {
        return $query->join('countries','companies.country_id','=','countries.id')->orderBy('countries.id',$sort)->select('companies.*');
    }

    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }


}
