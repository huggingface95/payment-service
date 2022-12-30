<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'country_id', 'name', 'address', 'bank_code', 'payment_system_code', 'payment_provider_id', 'payment_system_id', 'is_active',
    ];

    public function bankCorrespondent(): BelongsTo
    {
        return $this->belongsTo(BankCorrespondent::class, 'payment_system_id', 'payment_system_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }
}
