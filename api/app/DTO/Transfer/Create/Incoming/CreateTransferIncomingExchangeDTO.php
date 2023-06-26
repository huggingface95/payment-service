<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;

class CreateTransferIncomingExchangeDTO extends CreateTransferIncomingDTO
{
    public static function transform(Account $account, string $amount, CreateTransferOutgoingDTO $outgoingDTO, array $args): CreateTransferIncomingDTO
    {
        $args['account_id'] = $account->id;
        $args['currency_id'] = $account->currencies?->id;
        $args['company_id'] = $account->company_id;
        $args['amount'] = $amount;
        $args['amount_debt'] = $amount;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
        $args['payment_number'] = $outgoingDTO->payment_number;
        $args['payment_provider_id'] = $outgoingDTO->payment_provider_id;
        $args['payment_system_id'] = $outgoingDTO->payment_system_id;
        $args['payment_bank_id'] = $outgoingDTO->payment_bank_id;
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = $outgoingDTO->recipient_country_id;
        $args['respondent_fees_id'] = RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = $outgoingDTO->group_id;
        $args['group_type_id'] = $outgoingDTO->group_type_id;
        $args['price_list_id'] = $outgoingDTO->price_list_id;
        $args['price_list_fee_id'] = $outgoingDTO->price_list_fee_id;
        $args['requested_by_id'] = $outgoingDTO->requested_by_id;
        $args['created_at'] = $outgoingDTO->created_at;
        $args['execution_at'] = $outgoingDTO->created_at;
        $args['reason'] = 'Exchange: Sell';
        $args['project_id'] = $account->project_id;

        return new parent($args, $account);
    }
}
