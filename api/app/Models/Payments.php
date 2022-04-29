<?php

namespace App\Models;


use App\Models\Scopes\MemberScope;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class Payments extends BaseModel
{

    protected $table="payments";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'amount_real',
        'fee',
        'fee_type_id',
        'currency_id',
        'status_id',
        'sender_name',
        'payment_details',
        'sender_bank_account',
        'sender_swift',
        'sender_bank_name',
        'sender_bank_country',
        'sender_bank_address',
        'sender_country_id',
        'sender_address',
        'sender_email',
        'sender_phone',
        'urgency_id',
        'type_id',
        'payment_provider_id',
        'account_id',
        'company_id',
        'payment_number',
        'error',
        'member_id',
        'received_at',
        'sender_additional_fields'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new MemberScope);
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
        return $this->belongsTo(Currencies::class,'currency_id','id');
    }

    public function member()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    //TODO change to HasOneThrough  applicantIndividual
    public function owner()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'accounts','id', 'client_id', 'account_id');
    }

    public function company()
    {
        return $this->belongsToMany(ApplicantCompany::class,'accounts','id','client_id', 'account_id', 'owner_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class,'fee_type_id','id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class,'status_id','id');
    }

    public function applicantIndividual(): HasOneThrough
    {
        return $this->hasOneThrough(
            ApplicantIndividual::class,
            Accounts::class,
            'id',
            'id',
            'account_id',
            'client_id',
        );
    }

}
