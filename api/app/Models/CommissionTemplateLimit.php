<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CommissionTemplateLimit
 *
 * @property int id
 * @property int period_count
 * @property float amount

 *
 * @property CommissionTemplateLimitType $commissionTemplateLimitType
 * @property CommissionTemplateLimitTransferDirection $commissionTemplateLimitTransferDirection
 * @property CommissionTemplateLimitPeriod $commissionTemplateLimitPeriod
 * @property CommissionTemplateLimitActionType $commissionTemplateLimitActionType
 * @property Currencies $currency
 */
class CommissionTemplateLimit extends BaseModel
{
    public $timestamps = false;

    protected $table = 'commission_template_limit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'period_count',
        'amount',
        'currency_id',
        'commission_template_limit_type_id',
        'commission_template_limit_transfer_direction_id',
        'commission_template_limit_period_id',
        'commission_template_limit_action_type_id',
    ];

    public function commissionTemplateLimitType(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitType::class, 'commission_template_limit_type_id', 'id');
    }

    public function commissionTemplateLimitTransferDirection(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitTransferDirection::class, 'commission_template_limit_transfer_direction_id', 'id');
    }

    public function commissionTemplateLimitPeriod(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitPeriod::class, 'commission_template_limit_period_id', 'id');
    }

    public function commissionTemplateLimitActionType(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplateLimitActionType::class, 'commission_template_limit_action_type_id', 'id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }
}
