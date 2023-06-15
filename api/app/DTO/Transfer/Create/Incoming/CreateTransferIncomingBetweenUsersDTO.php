<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;

class CreateTransferIncomingBetweenUsersDTO extends CreateTransferIncomingDTO
{
    public static function transform(Account $toAccount, Account $fromAccount, int $operationType, array $args, string $paymentNumber, string $date): CreateTransferIncomingDTO
    {
        $args['account_id'] = $toAccount->id;
        $args['currency_id'] = $toAccount->currencies?->id;
        $args['company_id'] = $toAccount->company_id;
        $args['amount'] = $args['amount'];
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = $paymentNumber;
        $args['payment_provider_id'] = $toAccount->company->paymentProviderInternal?->id ?? throw new GraphqlException('Internal Payment provider not found');
        $args['payment_system_id'] = $toAccount->company->paymentSystemInternal?->id ?? throw new GraphqlException('Internal Payment system not found');
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = $fromAccount->clientable?->country_id ?? throw new GraphqlException('Sender country not found');
        $args['respondent_fees_id'] = $args['respondent_fee_id'] ?? RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['requested_by_id'] = 1;
        $args['created_at'] = $date;
        $args['execution_at'] = $date;

        return new parent($args, $toAccount);
    }
}
