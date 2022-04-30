<?php

namespace App\Models;




class PriceListFeesItem extends BaseModel
{


    protected $table="price_list_fees_item";

    protected $fillable = [
        'price_list_fees_id',
        'fee_item',
    ];

    public $timestamps = false;

    protected function getFeeItemAttribute($value)
    {
        return json_decode($value, true);
    }

    protected function setFeeItemAttribute($input)
    {
        $this->attributes['fee_item'] = json_encode($input);
    }

}
