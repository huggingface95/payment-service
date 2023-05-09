<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use Carbon\Carbon;

/**
 * @throws GraphqlException
 */
class CreateTransferOutgoingBetweenUsersDTO extends CreateTransferOutgoingDTO
{
    public static function transform(Account $account, int $operationType, array $args): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();

        $args['account_id'] = $account->id;
        $args['currency_id'] = $account->currencies?->id;
        $args['company_id'] = $account->company_id;
        $args['amount'] = $args['amount'];
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = $args['urgency_id'] ?? PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = 'BTW' . rand();
        $args['payment_provider_id'] = $account->company->paymentProviderInternal?->id ?? throw new GraphqlException('Internal Payment provider not found');
        $args['payment_system_id'] = $account->company->paymentSystemInternal?->id ?? throw new GraphqlException('Internal Payment system not found');;
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = 1;
        $args['respondent_fees_id'] = 2;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['price_list_id'] = $args['price_list_id'] ?? 1;
        $args['price_list_fee_id'] = $args['price_list_fee_id'] ?? 121;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $date->format('Y-m-d H:i:s');
        $args['recipient_bank_country_id'] = 1;
        $args['recipient_country_id'] = 1;

        return new parent($args, $account);
    }
}
