<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\ClientTypeEnum;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Illuminate\Support\Facades\Auth;

class CreateTransferOutgoingDTO
{
    public string $user_type;
    public string $amount_debt;
    public int $status_id;
    public int $operation_type_id;
    public int $payment_bank_id;
    public string $payment_number;
    public int $sender_id;
    public string $sender_type;
    public string $system_message;
    public string $channel;
    public string $reason;
    public int $recipient_country_id;
    public int $respondent_fees_id;
    public string $created_at;

    public int $company_id;
    public int $group_id;
    public int $group_type_id;
    public int $project_id;
    public string $amount;
    public int $currency_id;
    public int $account_id;
    public int $payment_provider_id;
    public int $payment_system_id;
    public int $recipient_bank_country_id;
    public int $requested_by_id;
    public int $price_list_id;
    public int $price_list_fee_id;
    public int $urgency_id;
    public string $execution_at;
    public string $recipient_account;
    public string $recipient_bank_name;
    public string $recipient_bank_address;
    public string $recipient_bank_swift;
    public string $recipient_name;
    public string $recipient_city;
    public string $recipient_address;
    public string $recipient_state;
    public string $recipient_zip;
    public string $bank_message;
    public array $file_id;
    public array $transfer_swift;

    protected array $general;

    protected function __construct(array $args, Account $account)
    {
        $properties = collect((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC))->pluck('name')->toArray();

        foreach ($args + $this->mergeGeneralData($account) as $k => $v) {
            if (in_array($k, $properties)) {
                $this->{$k} = $v;
            }
        }
    }


    //TODO add other parameters too
    private  function mergeGeneralData(Account $account): array
    {
        $clientType = Auth::guard('api') ? ClientTypeEnum::MEMBER->toString() : ClientTypeEnum::APPLICANT->toString();
        $id = Auth::guard('api')->user()?->id ?? Auth::guard('api_client')->user()?->id;

        return [
            'user_type' => $clientType == ClientTypeEnum::MEMBER->toString() ? class_basename(Members::class) : class_basename(ApplicantIndividual::class),
            'recipient_id' => $id,
            'recipient_type' => $clientType == ClientTypeEnum::MEMBER->toString() ? class_basename(ApplicantCompany::class) : class_basename(ApplicantIndividual::class),
            'requested_by_id' => auth()->user()?->id,
            'sender_id' => $id,
            'sender_type' => $clientType == ClientTypeEnum::MEMBER->toString() ? class_basename(ApplicantCompany::class) : class_basename(ApplicantIndividual::class),
        ];
    }



    public function toArray(): array
    {
        return (array)$this;
    }
}
