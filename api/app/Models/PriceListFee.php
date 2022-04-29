<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class PriceListFee
 * @package App\Models
 * @property int id
 * @property string name
 * @property int price_list_id
 * @property int type
 * @property int operation_type
 * @property int period
 * @property object fee
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class PriceListFee extends Model
{

    const RANGE = 'range';
    const FIX = 'fix';
    const RANGE_COLUMNS = ['amount_from', 'amount_to', 'mode'];
    const FIX_COLUMNS = ['fee', 'currency', 'mode'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'price_list_id', 'type_id', 'operation_type_id', 'period_id'];

    protected function getFeeAttribute($value)
    {
        return json_decode($value, true);
    }

    protected function setFeeAttribute($input)
    {
        $data = [];
        foreach ($input as $value) {
            $flag = $value['mode'];

            $data[] = array_filter($value, function ($k) use ($flag) {
                return in_array($k, $flag == self::RANGE ? self::RANGE_COLUMNS : self::FIX_COLUMNS);
            }, ARRAY_FILTER_USE_KEY);
        }

        $this->attributes['fee'] = json_encode($data);
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(CommissionPriceList::class, 'price_list_id');
    }

    public function fees()
    {
        return $this->hasMany(PriceListModeFees::class,'price_list_fees_id');
    }

    public function operationType()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function feePeriod()
    {
        return $this->belongsTo(OperationType::class, 'period_id');
    }

    public function feeType()
    {
        return $this->belongsTo(OperationType::class, 'type_id');
    }
}
