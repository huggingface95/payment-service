<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'company_id',
    ];

    public $timestamps = false;

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'region_countries', 'region_id', 'country_id')->using(RegionCountry::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function PaymentSystem(): BelongsToMany
    {
        return $this->belongsToMany(PaymentSystem::class, 'payment_system_regions', 'region_id', 'payment_system_id', 'id');
    }
}
