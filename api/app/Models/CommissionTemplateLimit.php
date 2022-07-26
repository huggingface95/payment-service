<?php

namespace App\Models;

use App\Models\Relationships\CustomHasMany;
use App\Models\Relationships\CustomHasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @method static whereIn(string $string, mixed $commission_template_limit_id)
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

    public function accounts(): CustomHasMany
    {
        $query = Account::query()
            ->leftJoin('commission_template', 'commission_template.id', '=', 'accounts.commission_template_id')
            ->select('accounts.*', 'commission_template.id as c_t_id');

        return new CustomHasMany(
            $query,
            $this,
            'commission_template.id',
            'commission_template_id',
            'c_t_id',
            $query
        );
    }

    public function region(): CustomHasOne
    {
        $query = Region::query()
            ->leftJoin('commission_template_regions', 'commission_template_regions.region_id', '=', 'regions.id')
            ->leftJoin('commission_template', 'commission_template.id', '=', 'commission_template_regions.commission_template_id')
            ->select('regions.*', 'commission_template.id as c_t_id');

        return new CustomHasOne(
            $query,
            $this,
            'commission_template.id',
            'commission_template_id',
            'c_t_id',
            $query
        );
    }

    public function paymentSystem(): CustomHasOne
    {
        $query = PaymentSystem::query()
            ->leftJoin('payment_provider_payment_system', 'payment_provider_payment_system.payment_system_id', '=', 'payment_system.id')
            ->leftJoin('commission_template', 'commission_template.payment_provider_id', '=', 'payment_provider_payment_system.payment_provider_id')
            ->select('payment_system.*', 'commission_template.id as c_t_id');

        return new CustomHasOne(
            $query,
            $this,
            'commission_template.id',
            'commission_template_id',
            'c_t_id',
            $query
        );
    }
}
