<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url','email','company_number','contact_name','country_id','zip','address','city'
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

    public function scopeMemberSort($query, $sort)
    {
        return $query->withCount('members')->orderBy('members_count',$sort);
    }

}
