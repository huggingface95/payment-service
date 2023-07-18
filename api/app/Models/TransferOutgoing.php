<?php

namespace App\Models;

use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\FeeTypeEnum;
use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Scopes\FilterByCreatorScope;
use App\Models\Scopes\TransferAmountSentScope;
use App\Models\Scopes\TransferFeeAmountScope;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\TransferOutgoingObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class TransferOutgoing
 *
 * @property Account $account
 * @property Currencies $currency
 * @property int requested_by_id
 * @property string user_type
 * @property int sender_id
 * @property int status_id
 * @property int account_id
 * @property float $amount
 * @property float amount_debt
 * @property Carbon execution_at
 */
class TransferOutgoing extends BaseModel implements CustomObServerInterface
{
    use HasFactory;
    use BaseObServerTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requested_by_id',
        'user_type',
        'amount',
        'amount_debt',
        'currency_id',
        'status_id',
        'urgency_id',
        'operation_type_id',
        'payment_provider_id',
        'payment_system_id',
        'payment_bank_id',
        'payment_number',
        'account_id',
        'sender_id',
        'sender_type',
        'company_id',
        'system_message',
        'reason',
        'channel',
        'bank_message',
        'beneficiary_type_id',
        'beneficiary_register_number',
        'recipient_account',
        'recipient_bank_name',
        'recipient_bank_address',
        'recipient_bank_swift',
        'recipient_bank_country_id',
        'recipient_name',
        'recipient_country_id',
        'recipient_city',
        'recipient_address',
        'recipient_state',
        'recipient_zip',
        'created_at',
        'updated_at',
        'execution_at',
        'respondent_fees_id',
        'group_id',
        'group_type_id',
        'project_id',
        'price_list_id',
        'price_list_fee_id',
        'fee_amount',
        'amount_sent',
    ];

    protected $casts = [
        'amount' => 'decimal:5',
        'amount_debt' => 'decimal:5',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'execution_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new TransferFeeAmountScope(true));
        static::addGlobalScope(new FilterByCreatorScope());
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function commissionPriceList(): HasOneThrough
    {
        return $this->hasOneThrough(CommissionPriceList::class, PaymentProvider::class, 'id', 'provider_id', 'payment_provider_id', 'id');
    }

    public function sender(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }

    public function clientable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'requested_by_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }

    public function fee(): HasOne
    {
        return $this->hasOne(Fee::class, 'transfer_id')
            ->where('transfer_type', FeeTransferTypeEnum::OUTGOING->toString())
            ->where('fee_type_id', FeeTypeEnum::FEES->value);
    }

    public function feeModeBase(): HasOne
    {
        return $this->fee()->where('fee_type_mode_id', FeeModeEnum::BASE->value);
    }

    public function feeModeProvider(): HasOne
    {
        return $this->fee()->where('fee_type_mode_id', FeeModeEnum::PROVIDER->value);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'transfer_id')->where('transfer_type', FeeTransferTypeEnum::OUTGOING->toString());
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(Files::class, TransferFIleRelation::class, 'transfer_id', 'file_id')
            ->where('transfer_type', class_basename(self::class));
    }

    public function paymentBank(): BelongsTo
    {
        return $this->belongsTo(PaymentBank::class, 'payment_bank_id');
    }

    public function paymentOperation(): BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }

    public function paymentProviderHistory(): HasOne
    {
        return $this->hasOne(PaymentProviderHistory::class, 'transfer_id', 'id')
            ->where('transfer_type', FeeTransferTypeEnum::OUTGOING->toString());
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }

    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }

    public function paymentUrgency(): BelongsTo
    {
        return $this->belongsTo(PaymentUrgency::class, 'urgency_id', 'id');
    }

    public function priceListFee(): BelongsTo
    {
        return $this->belongsTo(PriceListFee::class, 'price_list_fee_id', 'id');
    }

    public function recipientBankCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'recipient_bank_country_id', 'id');
    }

    public function recipientCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'recipient_country_id', 'id');
    }

    public function respondentFee(): BelongsTo
    {
        return $this->belongsTo(RespondentFee::class, 'respondent_fees_id');
    }

    public function transferType(): HasOneThrough
    {
        return $this->hasOneThrough(TransferType::class, OperationType::class, 'id', 'id', 'operation_type_id', 'transfer_type_id');
    }

    public function transferHistory(): HasMany
    {
        return $this->hasMany(TransferOutgoingHistory::class, 'transfer_id');
    }

    public function transferSwift(): HasMany
    {
        return $this->hasMany(TransferSwift::class, 'transfer_id', 'id')
            ->where('transfer_type', class_basename(self::class));
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transactions::class, 'transfer_id')
            ->where('transfer_type', class_basename(self::class));
    }

    public function exchange(): HasOne
    {
        return $this->hasOne(TransferExchange::class, 'transfer_outgoing_id');
    }

    public static function getObServer(): string
    {
        return TransferOutgoingObserver::class;
    }
}
