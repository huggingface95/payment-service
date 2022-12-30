<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BankCorrespondent extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bank_code',
        'bank_account',
        'payment_system_id',
        'is_active',
    ];

    public function bankCorrespondentCurrencies(): HasMany
    {
        return $this->hasMany(BankCorrespondentCurrency::class);
    }

    public function bankCorrespondentRegions(): HasMany
    {
        return $this->hasMany(BankCorrespondentRegion::class);
    }

    public function currencies(): HasManyThrough
    {
        return $this->hasManyThrough(
            Currencies::class,
            BankCorrespondentCurrency::class,
            'bank_correspondent_id',
            'id',
            'id',
            'currency_id'
        );
    }

    public function regions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Region::class,
            BankCorrespondentRegion::class,
            'bank_correspondent_id',
            'id',
            'id',
            'region_id'
        );
    }
}
