<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;


    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'region_countries', 'region_id', 'country_id');
    }
}
