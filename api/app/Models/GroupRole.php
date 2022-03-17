<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    public $timestamps = false;
    protected $table = 'group_role';

    protected $fillable = [
        'group_id', 'role_id','payment_provider_id','commission_template_id','is_active','description'
    ];

    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }

    public function role()
    {
        return $this->belongsTo(Role::class,"role_id");
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,"payment_provider_id");
    }

    public function commissionTemplate()
    {
        return $this->belongsTo(CommissionTemplate::class,"commission_template_id");
    }

}
