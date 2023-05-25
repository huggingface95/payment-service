<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BankCorrespondentCurrencyRegion extends Pivot
{
    use BaseObServerTrait;

    protected $table = 'bank_correspondent_currencies_regions';

    public function currency()
    {
        return $this->belongsTo(Currencies::class, 'currency_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
