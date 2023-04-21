<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use Carbon\Carbon;

class CreateTransferIncomingStandardDTO extends CreateTransferIncomingDTO
{

    public static function transform(array $args, int $operationType): CreateTransferIncomingDTO
    {
        $date = Carbon::now();

        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['reason'] = 'test';
        $args['sender_country_id'] = 1;
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return new parent($args, Account::findOrFail($args['account_id']));
    }
}
