<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PaymentBankCurrencyRegion extends Pivot
{
    protected $table = 'payment_bank_currencies_regions';
}
