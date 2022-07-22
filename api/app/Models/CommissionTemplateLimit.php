<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Class CommissionTemplateLimit
 *
 * @property int id
 * @property int period_count
 * @property float amount
 * @property CommissionTemplateLimitType $commissionTemplateLimitType
 * @property CommissionTemplateLimitTransferDirection $commissionTemplateLimitTransferDirection
 * @property CommissionTemplateLimitPeriod $commissionTemplateLimitPeriod
 * @property CommissionTemplateLimitActionType $commissionTemplateLimitActionType
 * @property Currencies $currency
 *
 *@method static whereIn(string $string, mixed $commission_template_limit_id)
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
        'commission_template_id',
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

    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id');
    }

    public function scopeAccountId(Builder $query, $accountId): Builder
    {
        return $query->select('commission_template_limit.*')->join('accounts','accounts.commission_template_id','=','commission_template_limit.commission_template_id')
            ->where('accounts.id','=',$accountId);
    }
}
