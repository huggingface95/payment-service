<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\FeeTypeEnum;
use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Scopes\FilterByCreatorScope;
use App\Models\Scopes\TransferFeeAmountScope;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\TransferIncomingObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class TransferIncoming
 *
 * @property TransferOutgoing $transferBetweenOutgoing
 * @property ApplicantIndividual|ApplicantCompany $recipient
 * @property Account $account
 * @property string recipient_type
 * @property int recipient_id
 * @property int requested_by_id
 * @property string user_type
 */
class TransferIncoming extends BaseModel implements CustomObServerInterface
{
    use HasFactory;
    use BelongsToOne;
    use BaseObServerTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        'recipient_id',
        'recipient_type',
        'company_id',
        'system_message',
        'reason',
        'channel',
        'bank_message',
        'beneficiary_type_id',
        'beneficiary_name',
        'sender_account',
        'sender_bank_name',
        'sender_bank_address',
        'sender_bank_swift',
        'sender_bank_country_id',
        'sender_bank_location',
        'sender_name',
        'sender_country_id',
        'sender_city',
        'sender_address',
        'sender_state',
        'sender_zip',
        'respondent_fees_id',
        'created_at',
        'updated_at',
        'execution_at',
        'group_id',
        'group_type_id',
        'project_id',
        'price_list_id',
        'price_list_fee_id',
        'fee_amount',
        'requested_by_id',
        'user_type',
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
        static::addGlobalScope(new TransferFeeAmountScope());
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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }

    public function fee(): HasOne
    {
        return $this->hasOne(Fee::class, 'transfer_id')
            ->where('transfer_type', FeeTransferTypeEnum::INCOMING->toString())
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
        return $this->hasMany(Fee::class, 'transfer_id')->where('transfer_type', FeeTransferTypeEnum::INCOMING->toString());
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
        return $this->belongsTo(OperationType::class, 'operation_type_id', 'id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }

    public function paymentProviderHistory(): HasOne
    {
        return $this->hasOne(PaymentProviderHistory::class, 'transfer_id', 'id')
            ->where('transfer_type', FeeTransferTypeEnum::INCOMING->toString());
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

    public function recipient(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'recipient_type', 'recipient_id');
    }

    public function respondentFee(): BelongsTo
    {
        return $this->belongsTo(RespondentFee::class, 'respondent_fees_id');
    }

    public function senderBankCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'sender_bank_country_id', 'id');
    }

    public function senderCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'sender_country_id', 'id');
    }

    public function transferType(): HasOneThrough
    {
        return $this->hasOneThrough(TransferType::class, OperationType::class, 'id', 'id', 'operation_type_id', 'transfer_type_id');
    }

    public function transferHistory(): HasMany
    {
        return $this->hasMany(TransferIncomingHistory::class, 'transfer_id');
    }

    public function transferSwift(): HasOne
    {
        return $this->hasOne(TransferSwift::class, 'transfer_id')
            ->where('transfer_type', class_basename(self::class));
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transactions::class, 'transfer_id')
            ->where('transfer_type', class_basename(self::class));
    }

    public function transferBetweenOutgoing(): \Ankurk91\Eloquent\Relations\BelongsToOne
    {
        return $this->belongsToOne(TransferOutgoing::class, TransferBetween::class, 'transfer_incoming_id', 'transfer_outgoing_id');
    }

    public function exchange(): HasOne
    {
        return $this->hasOne(TransferExchange::class, 'transfer_incoming_id');
    }

    public static function getObServer(): string
    {
        return TransferIncomingObserver::class;
    }
}
