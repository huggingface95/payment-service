<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentSystem extends BaseModel
{
    public $timestamps = false;

    protected $table = 'payment_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active',
    ];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, 'payment_system_currencies', 'payment_system_id', 'currency_id');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'payment_system_countries', 'payment_system_id', 'country_id');
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(PaymentProvider::class, 'payment_provider_payment_system', 'payment_system_id', 'payment_provider_id');
    }
}
