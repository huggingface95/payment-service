<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use Carbon\Carbon;

class CreateTransferOutgoingScheduledFeeDTO extends CreateTransferOutgoingDTO
{
    public static function transform(array $args): CreateTransferOutgoingDTO
    {
        /** @var PaymentSystem $psInternal */
        $psInternal = PaymentSystem::query()->where('name', 'Internal')->first();
        /** @var PaymentProvider $ppInternal */
        $ppInternal = PaymentProvider::query()->where('name', 'Internal')->first();
        $date = Carbon::now();

        $args['amount_debt'] = $args['amount'];
        $args['company_id'] = 1;
        $args['recipient_bank_country_id'] = 1;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['price_list_id'] = 1;
        $args['price_list_fee_id'] = 1;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = $args['urgency_id'] ?? PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = OperationTypeEnum::SCHEDULED_FEE->value;
        $args['payment_provider_id'] = $ppInternal->id;
        $args['payment_system_id'] = $psInternal->id;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['recipient_country_id'] = 1;
        $args['respondent_fees_id'] = 1;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return new parent($args, Account::findOrFail($args['account_id']));
    }
}
