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
 * Class TransferOutgoing
 */
class TransferOutgoing extends BaseModel
{
    use HasFactory;

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
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->withoutGlobalScope(AccountIndividualsCompaniesScope::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'transfer_id')->where('transfer_type', FeeTransferTypeEnum::OUTGOING->toString());
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

}
