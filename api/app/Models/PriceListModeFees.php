<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class PriceListModeFees extends BaseModel
{
    protected $table="price_list_mode_fees";

    protected $fillable = [
        'fees_mode_id',
        'price_list_fees_id',
        'fee',
        'fee_from',
        'fee_to',
    ];

    public $timestamps = false;

    public function currencies()
    {
        return $this->belongsToMany(Currencies::class,'mode_fee_currency','price_list_mode_fee_id','currency_id');
    }

    public function mode()
    {
        return $this->belongsTo(FeesMode::class, 'fees_mode_id');
    }

}
