<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class CommissionTemplate extends BaseModel
{

    public $timestamps = false;

    protected $table = "commission_template";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'description', 'payment_provider_id', 'country_id', 'currency_id', 'commission_template_limit_id', 'member_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_id', function (Builder $builder) {
            $memberId = self::DEFAULT_MEMBER_ID;
            $companyId = Members::where('id', '=', $memberId)->value('company_id');
            $companyMembers = Members::where('company_id', '=', $companyId)->get('id');
            $result = collect($companyMembers)->pluck('id')->toArray();
            $builder->whereIn('member_id', $result);
        });
    }


    /**
     * Get relation currencies
     * @return BelongsToMany
     */
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, 'commission_template_currency', 'commission_template_id', 'currency_id');
    }

    /**
     * Get relation countries
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'commission_template_country', 'commission_template_id', 'country_id');
    }

    /**
     * Get relation bussiness activities
     * @return BelongsToMany
     */
    public function businessActivity(): BelongsToMany
    {
        return $this->belongsToMany(BusinessActivity::class, 'commission_template_business_activity', 'commission_template_id', 'business_activity_id');
    }

    /**
     * Get relation commission template limits
     * @return BelongsToMany
     */
    public function commissionTemplateLimits(): BelongsToMany
    {
        return $this->belongsToMany(CommissionTemplateLimit::class, 'commission_template_limit_relation', 'commission_template_id', 'commission_template_limit_id');
    }

    /**
     * Get relation payment provider
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }


    public function scopePaymentProviderName(Builder $query, $sort): Builder
    {
        return $query->leftJoin(
            DB::raw('(SELECT id, name as payment_provider_name FROM "payment_provider") p'),
            function($join)
            {
                $join->on('commission_template.id', '=', 'p.id');
            })
            ->orderBy('p.payment_provider_name', $sort)
            ->selectRaw('commission_template.*');
    }

    public function owner(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'accounts', 'commission_template_id', 'client_id', 'id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'id', 'commission_template_id');
    }

    public function company(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantCompany::class, 'accounts', 'commission_template_id', 'client_id', 'id', 'owner_id');
    }

    public function commissionTemplateLimit(): BelongsToMany
    {
        return $this->belongsToMany(CommissionTemplateLimit::class, 'commission_template_limit_relation', 'commission_template_id', 'commission_template_limit_id');
    }


}
