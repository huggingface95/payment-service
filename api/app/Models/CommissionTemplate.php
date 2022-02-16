<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class CommissionTemplate extends BaseModel
{

    public $timestamps = false;

    protected $table="commission_template";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active','description','payment_provider_id','country_id','currency_id','commission_template_limit_id', 'member_id'
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
     * Get relation currencies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currencies()
    {
        return $this->belongsToMany(Currencies::class,'commission_template_currency','commission_template_id','currency_id');
    }

    /**
     * Get relation countries
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class,'commission_template_country','commission_template_id','country_id');
    }

    /**
     * Get relation bussiness activities
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businessActivity()
    {
        return $this->belongsToMany(BusinessActivity::class,'commission_template_business_activity','commission_template_id','business_activity_id');
    }

    /**
     * Get relation commission template limits
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function commissionTemplateLimits()
    {
        return $this->belongsToMany(CommissionTemplateLimit::class,'commission_template_limit_relation','commission_template_id','commission_template_limit_id');
    }

    /**
     * Get relation payment provider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,'payment_provider_id','id');
    }

    public function scopePaymentProviderName($query, $sort)
    {
        return $query->join('payment_provider','commission_template.payment_provider_id','=','payment_provider.id')->orderBy('payment_provider.name',$sort)->select('commission_template.*');
    }

    public function owner()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    public function account()
    {
        return $this->belongsTo(Accounts::class,'member_id','owner_id');
    }

    public function company()
    {
        return $this->belongsToMany(Companies::class,'members','id','company_id');
    }





}
