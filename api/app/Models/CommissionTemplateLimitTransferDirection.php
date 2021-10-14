<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionTemplateLimitTransferDirection extends Model
{

    public $timestamps = false;

    protected $table="commission_template_limit_transfer_direction";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
