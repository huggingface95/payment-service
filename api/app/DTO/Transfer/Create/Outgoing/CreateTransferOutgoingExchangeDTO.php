<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTransferOutgoingExchangeDTO extends CreateTransferOutgoingDTO
{
    public static function transform(Account $account, string $amount, int $price_list_fee_id): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();

        $args['account_id'] = $account->id;
        $args['currency_id'] = $account->currencies?->id;
        $args['company_id'] = $account->company_id;
        $args['amount'] = $amount;
        $args['amount_debt'] = $amount;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = Str::uuid();
        $args['payment_provider_id'] = 1;
        $args['payment_system_id'] = 1;
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = 1;
        $args['respondent_fees_id'] = RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['price_list_id'] = 1;
        $args['price_list_fee_id'] = $price_list_fee_id;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $date->format('Y-m-d H:i:s');
        $args['recipient_bank_country_id'] = 1;
        $args['recipient_country_id'] = 1;

        return new parent($args, $account);
    }
}
