<?php

namespace App\Models;



class Transactions extends BaseModel
{

    protected $table="transactions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'payment_id',
        'currency_src_id',
        'currency_dst_id',
        'account_src_id',
        'account_dst_id',
        'balance_prev',
        'balance_next',
        'amount',
        'txtype'
    ];



}