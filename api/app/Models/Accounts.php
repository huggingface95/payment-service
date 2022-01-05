<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class Accounts extends BaseModel
{

    public $timestamps = false;

    protected $table="accounts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id', 'client_id', 'owner_id', 'account_id', 'account_type', 'payment_provider_id', 'commission_template_id', 'account_state', 'account_name', 'is_primary', 'current_balance', 'reserved_balance', 'available_balance'
    ];


    /**
     * Get relation currencies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Currencies()
    {
        return $this->belongsTo(Currencies::class,'currency_id','id');
    }

    /**
     * Get relation applicant individual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'client_id','id');
    }

    /**
     * Get relation Member
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Members()
    {
        return $this->belongsTo(Members::class,'owner_id','id');
    }

    /**
     * Get relation Payment Provider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PaymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,'payment_provider_id','id');
    }

    /**
     * Get relation Payment Provider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CommissionTemplate()
    {
        return $this->belongsTo(CommissionTemplate::class,'commission_template_id','id');
    }

    public function setAccountIdAttribute()
    {
        $this->attributes['account_id']=uniqid();
    }


}
