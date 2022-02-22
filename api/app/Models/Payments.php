<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class Payments extends BaseModel
{

    protected $table="payments";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'amount', 'fee', 'currency', 'status', 'sender_name', 'payment_details', 'sender_bank_account', 'sender_swift', 'sender_bank_name', 'sender_bank_country', 'sender_bank_address', 'sender_country', 'sender_address', 'urgency_id', 'type_id', 'payment_provider_id', 'account_id', 'company_id', 'payment_number', 'member_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_id', function ($builder) {
            $memberId = self::DEFAULT_MEMBER_ID;
            $companyId = Members::where('id', '=', $memberId)->value('company_id');
            $companyMembers = Members::where('company_id', '=', $companyId)->get('id');
            $result = collect($companyMembers)->pluck('id')->toArray();
            return $builder->whereIn('member_id', $result);
        });
    }


    /**
     * Get relation Country
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }


    /**
     * Get relation applicant Account
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Accounts()
    {
        return $this->belongsTo(Accounts::class,'account_id','id');
    }

    /**
     * Get relation Companies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Companies()
    {
        return $this->belongsTo(Companies::class,'company_id','id');
    }

    /**
     * Get relation payment_urgency
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PaymentUrgency()
    {
        return $this->belongsTo(PaymentUrgency::class,'urgency_id','id');
    }

    /**
     * Get relation PaymentTypes
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PaymentTypes()
    {
        return $this->belongsTo(PaymentTypes::class,'type_id','id');
    }

    /**
     * Get relation PaymentProvider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PaymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,'payment_provider_id','id');
    }

    /**
     * Get relation Currencies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Currencies()
    {
        return $this->belongsTo(Currencies::class,'currency','id');
    }

    public function member()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    public function owner()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'accounts','id', 'client_id', 'account_id');
    }

    public function company()
    {
        return $this->belongsToMany(ApplicantCompany::class,'accounts','id','client_id', 'account_id', 'owner_id');
    }


}
