<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\Pivot;

class RegionCountry extends Pivot
{
    protected $table = 'region_countries';

    protected $fillable = [
        'region_id', 'country_id'
    ];

    public $timestamps = false;

}
