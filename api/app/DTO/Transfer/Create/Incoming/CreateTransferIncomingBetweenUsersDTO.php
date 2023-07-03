<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingDTO;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;

class CreateTransferIncomingBetweenUsersDTO extends CreateTransferIncomingDTO
{
    public static function transform(Account $toAccount, Account $fromAccount, int $operationType, array $args, CreateTransferOutgoingDTO $outgoingDTO): CreateTransferIncomingDTO
    {
        $args['account_id'] = $toAccount->id;
        $args['currency_id'] = $toAccount->currencies?->id;
        $args['company_id'] = $toAccount->company_id;
        $args['amount'] = $args['amount'];
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_number'] = $outgoingDTO->payment_number;
        $args['payment_provider_id'] = $outgoingDTO->payment_provider_id;
        $args['payment_system_id'] = $outgoingDTO->payment_system_id;
        $args['payment_bank_id'] = $outgoingDTO->payment_bank_id;
        $args['system_message'] = '';
        $args['channel'] = $outgoingDTO->channel;
        $args['sender_country_id'] = $fromAccount->clientable?->country_id ?? throw new GraphqlException('Sender country not found');
        $args['respondent_fees_id'] = $args['respondent_fee_id'] ?? RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = $toAccount->group_role_id;
        $args['group_type_id'] = $toAccount->group_type_id;
        $args['created_at'] = $outgoingDTO->created_at;
        $args['execution_at'] = $outgoingDTO->created_at;
        $args['project_id'] = $toAccount->project_id;

        return new parent($args, $toAccount);
    }
}
