<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionTemplateLimitType extends Model
{

    const ALL = 'All';
    const TRANSACTION_AMOUNT = 'Transaction Amount';
    const TRANSACTION_COUNT = 'Transaction Count';
    const TRANSFER_COUNT = 'Transfer Count';

    public $timestamps = false;

    protected $table="commission_template_limit_type";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
