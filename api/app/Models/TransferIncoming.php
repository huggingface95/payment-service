<?php

namespace App\Models;

use App\Enums\FeeTransferTypeEnum;
use App\Enums\FeeTypeEnum;
use App\Models\Scopes\AccountIndividualsCompaniesScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class TransferIncoming
 */
class TransferIncoming extends BaseModel
{
    use HasFactory;

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
        'sender_account',
        'sender_bank_name',
        'sender_bank_address',
        'sender_bank_swift',
        'sender_bank_country_id',
        'sender_name',
        'sender_country_id',
        'sender_city',
        'sender_address',
        'sender_state',
        'sender_zip',
        'created_at',
        'updated_at',
        'execution_at',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->withoutGlobalScope(AccountIndividualsCompaniesScope::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id', 'id');
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

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'transfer_id')->where('transfer_type', FeeTransferTypeEnum::INCOMING->toString());
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

}
