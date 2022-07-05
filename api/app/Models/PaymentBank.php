<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentBank extends BaseModel
{
    public $timestamps = false;

    protected $table = 'payment_banks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'name', 'address', 'bank_code', 'payment_system_code',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function paymentSystems(): BelongsToMany
    {
        return $this->belongsToMany(PaymentSystem::class, 'payment_system_banks', 'payment_bank_id', 'payment_system_id');
    }

}
