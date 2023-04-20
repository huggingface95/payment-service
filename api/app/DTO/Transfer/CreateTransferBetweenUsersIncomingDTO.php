<?php

namespace App\DTO\Transfer;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;

class CreateTransferBetweenUsersIncomingDTO extends CreateTransferBetweenUsersDTO
{

    public int $account_id;
    public int $currency_id;
    public int $company_id;
    public string $user_type;
    public string $amount;
    public string $amount_debt;
    public int $status_id;
    public int $urgency_id;
    public int $operation_type_id;
    public int $payment_bank_id;
    public string $payment_number;
    public int $payment_provider_id;
    public int $payment_system_id;
    public int $recipient_id;
    public string $recipient_type;
    public string $system_message;
    public string $channel;
    public string $reason;
    public int $sender_country_id;
    public int $respondent_fees_id;
    public int $group_id;
    public int $group_type_id;
    public int $project_id;
    public int $price_list_id;
    public int $price_list_fee_id;
    public string $created_at;
    public string $execution_at;

    public static function transform(array $args): self
    {
        $args = array_merge($args, parent::getGeneralData(self::class, CreateTransferBetweenUsersOutgoingDTO::class));

        $dto = new self();
        $dto->account_id = $args['account_id'];
        $dto->currency_id = $args['currency_id'];
        $dto->company_id = $args['company_id'];
        $dto->user_type = $args['user_type'];
        $dto->amount = $args['amount'];
        $dto->amount_debt = $args['amount'];
        $dto->status_id = PaymentStatusEnum::UNSIGNED->value;
        $dto->urgency_id = 1;
        $dto->operation_type_id = $args['operation_type_id'];
        $dto->payment_bank_id = 2;
        $dto->payment_number = $args['payment_number'];
        $dto->payment_provider_id = $args['payment_provider_id'];
        $dto->payment_system_id = $args['payment_system_id'];
        $dto->recipient_id = $args['recipient_id'];
        $dto->recipient_type = $args['recipient_type'];
        $dto->system_message = 'test';
        $dto->channel = TransferChannelEnum::BACK_OFFICE->toString();
        $dto->reason = 'test';
        $dto->sender_country_id = 1;
        $dto->respondent_fees_id = 2;
        $dto->group_id = 1;
        $dto->group_type_id = 1;
        $dto->project_id = 1;
        $dto->price_list_id = 1;
        $dto->price_list_fee_id = 121;
        $dto->created_at = $args['created_at'];
        $dto->execution_at = $args['execution_at'];

        return $dto;
    }

}
