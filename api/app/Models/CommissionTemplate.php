<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\MemberScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

/**
 * Class CommissionTemplate
 *
 * @property CommissionTemplateLimit $commissionTemplateLimits
 */
class CommissionTemplate extends BaseModel
{
    use BaseObServerTrait;

    public $timestamps = false;

    protected $table = 'commission_template';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'description', 'payment_provider_id', 'country_id', 'currency_id', 'commission_template_limit_id', 'member_id', 'company_id',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MemberScope());
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    /**
     * Get relation currencies
     *
     * @return BelongsToMany
     */
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currencies::class, 'commission_template_currency', 'commission_template_id', 'currency_id');
    }

    /**
     * Get relation countries
     *
     * @return BelongsToMany
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'commission_template_regions', 'commission_template_id', 'region_id');
    }

    /**
     * Get relation bussiness activities
     *
     * @return BelongsToMany
     */
    public function businessActivity(): BelongsToMany
    {
        return $this->belongsToMany(BusinessActivity::class, 'commission_template_business_activity', 'commission_template_id', 'business_activity_id');
    }

    public function commissionTemplateLimits(): HasMany
    {
        return $this->hasMany(CommissionTemplateLimit::class, 'commission_template_id');
    }

    /**
     * Get relation payment provider
     *
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
            function ($join) {
                $join->on('p.id', '=', 'commission_template.payment_provider_id');
            }
        )
            ->orderBy('p.payment_provider_name', $sort)
            ->selectRaw('commission_template.*');
    }

    public function owner(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'accounts', 'commission_template_id', 'owner_id', 'id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'id', 'commission_template_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function paymentSystem(): HasManyThrough
    {
        return $this->HasManyThrough(PaymentSystem::class, PaymentProvider::class, 'id', 'payment_provider_id', 'payment_provider_id', 'id');
    }
}
