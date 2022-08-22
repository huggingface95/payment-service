<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\MemberScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Class Payments
 *
 * @property int owner_id
 * @property float amount
 * @property int fee_type_id
 * @property int currency_id
 * @property float $fee
 * @property float amount_real
 * @property int $operation_type_id
 * @property ApplicantIndividual $applicantIndividual
 * @property CommissionPriceList $commissionPriceList
 * @property Account $account
 * @property OperationType $paymentOperation
 * @property Currencies $currency
 *
 * @method static find(mixed $id)
 */
class Payments extends BaseModel
{
    protected $table = 'payments';

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
        'operation_type_id',
        'payment_provider_id',
        'account_id',
        'company_id',
        'payment_number',
        'error',
        'member_id',
        'received_at',
        'sender_additional_fields',
        'owner_id',
        'created_at',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MemberScope());
        static::addGlobalScope(new ApplicantFilterByMemberScope);
        self::creating(function ($model) {
            $model->fee = CommissionTemplateLimit::query()
                ->join('commission_template AS ct', 'ct.id', '=', 'commission_template_limit.commission_template_id')
                ->join('commission_price_list as l', 'l.commission_template_id', '=', 'ct.id')
                ->join('payment_provider as p', 'p.id', '=', 'l.provider_id')
                ->where('p.id', $model->payment_provider_id)
                ->where('commission_template_limit_type_id', $model->fee_type_id)
                ->select('commission_template_limit.*')
                ->first()->amount ?? 0;
        });
    }

    public function scopeAccountNumber(Builder $query, $sort): Builder
    {
        return $query->join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->orderBy('accounts.account_number', $sort)
            ->selectRaw('payments.*');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id', 'id');
    }

    public function paymentUrgency(): BelongsTo
    {
        return $this->belongsTo(PaymentUrgency::class, 'urgency_id', 'id');
    }

    public function paymentOperation(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function applicantIndividual(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'owner_id');
    }

//    public function company()
//    {
//        return $this->belongsToMany(ApplicantCompany::class,'accounts','id','client_id', 'account_id', 'owner_id');
//    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }

    public function commissionPriceList(): HasOneThrough
    {
        return $this->hasOneThrough(CommissionPriceList::class, PaymentProvider::class, 'id', 'provider_id', 'payment_provider_id', 'id');
    }
}
