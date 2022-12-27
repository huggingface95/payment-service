<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

class PaymentSystem extends BaseModel
{
    use HasRelationships, HasTableAlias;

    public $timestamps = false;

    protected $table = 'payment_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'description',
        'logo_id',
    ];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, 'payment_system_currencies', 'payment_system_id', 'currency_id');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'payment_system_regions', 'payment_system_id', 'region_id');
    }

    public function providers(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(OperationType::class, 'payment_system_operation_types', 'payment_system_id', 'operation_type_id');
    }

    public function companies(): HasManyDeep
    {
        return $this->hasManyDeep(
            Company::class,
            [PaymentSystem::class, PaymentProvider::class],
            [
                'payment_provider_id',
                'id',
                'id',
            ],
            [
                'id',
                'payment_provider_id',
                'company_id',
            ],
        );
    }

    public function company(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->providers(), (new PaymentProvider())->company());
    }

    public function banks(): BelongsToMany
    {
        return $this->belongsToMany(PaymentBank::class, 'payment_system_banks', 'payment_system_id', 'payment_bank_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }
}
