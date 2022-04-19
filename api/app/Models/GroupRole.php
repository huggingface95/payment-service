<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupRole extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $table = 'group_role';

    protected $fillable = [
        'name','group_type_id', 'role_id','payment_provider_id','commission_template_id','is_active','description','company_id'
    ];

    public function groupType()
    {
        return $this->belongsTo(Groups::class,"group_type_id");
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

    public function company()
    {
        return $this->belongsTo(Companies::class,"company_id");
    }

}
