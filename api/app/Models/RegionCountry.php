<?php

namespace App\Models;

use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\RegionCountryObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RegionCountry extends Pivot implements CustomObServerInterface
{
    use BaseObServerTrait;

    protected $table = 'region_countries';

    protected $fillable = [
        'region_id', 'country_id',
    ];

    public $timestamps = false;

    public static function getObServer(): string
    {
        return RegionCountryObserver::class;
    }
}
