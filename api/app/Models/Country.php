<?php

namespace App\Models;

use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Country extends BaseModel
{
    use HasRelationships;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'iso',
    ];

    public $timestamps = false;


    public function paymentSystems(): HasManyDeep
    {
        return $this->hasManyDeep(
            PaymentSystem::class,
            ['region_countries', 'payment_system_regions'],
            [
                'country_id',
                'region_id',
                'id',
            ],
            [
                'id',
                'region_id',
                'payment_system_id',
            ],
        );
    }

}
