<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use App\Services\PriceListFeeService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class PriceListFee
 *
 * @property int id
 * @property string name
 * @property int price_list_id
 * @property int type
 * @property int operation_type_id
 * @property int period
 * @property object fee
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int type_id
 *
 * @method static findOrFail()
 */
class PriceListFee extends BaseModel
{
    use BaseObServerTrait;
    use SoftDeletes;
    public const RANGE = 'range';

    public const FIX = 'fix';

    public const RANGE_COLUMNS = ['amount_from', 'amount_to', 'mode'];

    public const FIX_COLUMNS = ['fee', 'currency', 'mode'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'price_list_id', 'type_id', 'operation_type_id', 'period_id', 'quote_provider_id', 'company_id'];

    protected $appends = ['fee_ranges'];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function getFeeRangesAttribute(): array
    {
        $fees = $this->fees()->get();

        return (new PriceListFeeService())->convertFeesToFeeRanges($fees);
    }

//    protected function getFeeAttribute($value)
//    {
//        return json_decode($value, true);
//    }

//    protected function setFeeAttribute($input)
//    {
//        $data = [];
//        foreach ($input as $value) {
//            $flag = $value['mode'];
//
//            $data[] = array_filter($value, function ($k) use ($flag) {
//                return in_array($k, $flag == self::RANGE ? self::RANGE_COLUMNS : self::FIX_COLUMNS);
//            }, ARRAY_FILTER_USE_KEY);
//        }
//
//        $this->attributes['fee'] = json_encode($data);
//    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(CommissionPriceList::class, 'price_list_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function paymentProvider(): HasOneThrough
    {
        return $this->hasOneThrough(
            PaymentProvider::class,
            CommissionPriceList::class,
            'id',
            'id',
            'price_list_id',
            'provider_id',
        );
    }

    public function commissionTemplate(): HasOneThrough
    {
        return $this->hasOneThrough(
            CommissionTemplate::class,
            CommissionPriceList::class,
            'id',
            'id',
            'price_list_id',
            'commission_template_id',
        );
    }

    public function paymentSystem(): HasOneThrough
    {
        return $this->hasOneThrough(
            PaymentSystem::class,
            CommissionPriceList::class,
            'id',
            'id',
            'price_list_id',
            'payment_system_id',
        );
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PriceListFeeCurrency::class, 'price_list_fee_id');
    }

    public function feeScheduled(): HasOne
    {
        return $this->hasOne(PriceListFeeScheduled::class, 'price_list_fee_id');
    }

    public function operationType(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function feePeriod(): BelongsTo
    {
        return $this->belongsTo(FeePeriod::class, 'period_id');
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class, 'type_id');
    }

    public function feeDestinationCurrency(): HasMany
    {
        return $this->hasMany(PriceListFeeDestinationCurrency::class, 'price_list_fee_currency_id');
    }

    public function quoteProvider(): BelongsTo
    {
        return $this->belongsTo(QuoteProvider::class, 'quote_provider_id');
    }
}
