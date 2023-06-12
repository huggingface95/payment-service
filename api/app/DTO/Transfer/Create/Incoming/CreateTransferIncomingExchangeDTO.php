<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;

class CreateTransferIncomingExchangeDTO extends CreateTransferIncomingDTO
{
    public static function transform(Account $account, string $amount, string $paymentNumber, string $date, int $price_list_fee_id): CreateTransferIncomingDTO
    {
        $args['account_id'] = $account->id;
        $args['currency_id'] = $account->currencies?->id;
        $args['company_id'] = $account->company_id;
        $args['amount'] = $amount;
        $args['amount_debt'] = $amount;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = $paymentNumber;
        $args['payment_provider_id'] = 1;
        $args['payment_system_id'] = 1;
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = 1;
        $args['respondent_fees_id'] = RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['price_list_id'] = 1;
        $args['price_list_fee_id'] = $price_list_fee_id;
        $args['requested_by_id'] = 2;
        $args['created_at'] = $date;
        $args['execution_at'] = $date;
        $args['reason'] = 'Exchange: Sell';

        return new parent($args, $account);
    }
}
