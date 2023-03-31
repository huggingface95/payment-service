<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BankCorrespondentCurrencyRegion extends Pivot
{
    protected $table = 'bank_correspondent_currencies_regions';
}
