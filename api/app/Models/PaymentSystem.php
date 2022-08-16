<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PaymentSystem extends BaseModel
{
    use HasRelationships;
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

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'payment_system_regions', 'payment_system_id', 'region_id');
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(PaymentProvider::class, 'payment_provider_payment_system', 'payment_system_id', 'payment_provider_id');
    }

    public function companies(): HasManyDeep
    {
        return $this->hasManyDeep(
            Companies::class,
            [PaymentProviderPaymentSystem::class, PaymentProvider::class], // Intermediate models, beginning at the far parent (Country).
            [
                'payment_system_id',     // Foreign key on the "comments" table.
                'id', // Foreign key on the "users" table.
                'id',    // Foreign key on the "posts" table.
            ],
            [
                'id', // Local key on the "countries" table.
                'payment_provider_id', // Local key on the "users" table.
                'company_id'  // Local key on the "posts" table.
            ]
        );
    }
}
