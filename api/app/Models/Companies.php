<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Companies
 *
 * @property int id
 *
 * @property CompanySettings $companySettings
 */
class Companies extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'email',
        'company_number',
        'contact_name',
        'country_id',
        'zip',
        'address',
        'city',
        'additional_fields',
        'phone',
        'reg_address',
        'tax_id',
        'incorporate_date',
        'employees_id',
        'type_of_industry_id',
        'license_number',
        'exp_date',
        'state_id',
        'state_reason_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function companySettings(): HasOne
    {
        return $this->hasOne(CompanySettings::class, 'company_id', 'id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo('App\Models\Languages', 'language_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Members::class, 'company_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Departments::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employees_id', 'id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(DepartmentPosition::class, 'company_id');
    }

    public function paymentProviders(): HasMany
    {
        return $this->hasMany(PaymentProvider::class, 'company_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function stateReason(): BelongsTo
    {
        return $this->belongsTo(stateReason::class, 'state_reason_id');
    }

    public function typeOfIndustry(): BelongsTo
    {
        return $this->belongsTo(TypeOfIndustry::class, 'type_of_industry_id');
    }

    public function scopeMemberSort($query, $sort)
    {
        return $query->withCount('members')->orderBy('members_count', $sort);
    }

    public function scopeCountrySort($query, $sort)
    {
        return $query->join('countries', 'companies.country_id', '=', 'countries.id')->orderBy('countries.id', $sort)->select('companies.*');
    }

    public function getMembersCountAttribute()
    {
        return $this->members()->count();
    }
}
