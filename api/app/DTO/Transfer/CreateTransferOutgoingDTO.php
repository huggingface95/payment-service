<?php

namespace App\DTO\Transfer;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateTransferOutgoingDTO
{
    public string $user_type;
    public float $amount_debt;
    public int $status_id;
    public int $operation_type_id;
    public int $payment_bank_id;
    public int $payment_number;
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

    /**
     * @throws GraphqlException
     */
    public static function transform(array $args, int $operationType): self
    {
        $date = Carbon::now();

        $dto = new self();
        $dto->user_type = Auth::guard('api')->check() ? class_basename(Members::class) : class_basename(ApplicantIndividual::class);
        $dto->amount_debt = $args['amount'];
        $dto->status_id = PaymentStatusEnum::UNSIGNED->value;
        $dto->operation_type_id = $operationType;
        $dto->payment_bank_id = 2;
        $dto->payment_number = rand();
        $dto->sender_id = 1;
        $dto->sender_type = class_basename(ApplicantCompany::class);
        $dto->system_message = 'test';
        $dto->channel = TransferChannelEnum::BACK_OFFICE->toString();
        $dto->reason = 'test';
        $dto->recipient_country_id = 1;
        $dto->respondent_fees_id = 2;
        $dto->created_at = $date->format('Y-m-d H:i:s');

        foreach ($args as $property => $value) {
            if (property_exists($dto, $property)) {
                $dto->{$property} = $value;
            }
        }

        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
        } else {
            $dto->execution_at = $dto->created_at;
        }

        return $dto;
    }
}
