<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AccountLimit
 * @package App\Models
 * @property int id
 * @property int period_count
 * @property float amount
 * @property int currency_id
 * @property int commission_template_limit_type_id
 * @property int commission_template_limit_transfer_direction_id
 * @property int commission_template_limit_period_id
 * @property int commission_template_limit_action_type_id
 *
 * @property CommissionTemplateLimitType $commissionTemplateLimitType
 * @property CommissionTemplateLimitTransferDirection $commissionTemplateLimitTransferDirection
 * @property CommissionTemplateLimitPeriod $commissionTemplateLimitPeriod
 * @property CommissionTemplateLimitActionType $commissionTemplateLimitActionType
 * @property Currencies $currency
 *
 */
class AccountLimit extends BaseModel
{


    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'period_count',
        'amount',
        'currency_id',
        'commission_template_limit_type_id',
        'commission_template_limit_transfer_direction_id',
        'commission_template_limit_period_id',
        'commission_template_limit_action_type_id',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'account_id');
    }

    public function commissionTemplateLimitType(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitType::class, 'commission_template_limit_type_id');
    }

    public function commissionTemplateLimitTransferDirection(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitTransferDirection::class, 'commission_template_limit_transfer_direction_id');
    }

    public function commissionTemplateLimitPeriod(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitPeriod::class, 'commission_template_limit_period_id');
    }

    public function commissionTemplateLimitActionType(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitActionType::class, 'commission_template_limit_action_type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id');
    }
}
