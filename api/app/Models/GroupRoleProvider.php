<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupRoleProvider extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_role_id',
        'payment_provider_id',
        'commission_template_id',
        'is_default',
    ];

    public $timestamps = false;

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }
}
