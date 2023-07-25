<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\BeneficiaryTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTransferOutgoingRefundDTO extends CreateTransferOutgoingDTO
{
    public static function transform(array $args, int $operationType): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();
        $account = Account::findOrFail($args['account_id']);

        $args['amount_debt'] = $args['amount'];
        $args['beneficiary_type_id'] = $account->account_type == BeneficiaryTypeEnum::PERSONAL->value ? BeneficiaryTypeEnum::PERSONAL->value : BeneficiaryTypeEnum::CORPORATE->value;
        $args['reason'] = 'Refund #'.$args['id'];
        $args['status_id'] = PaymentStatusEnum::REFUND->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['recipient_country_id'] = $args['sender_country_id'];
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = null;
        $args['project_id'] = $account->project_id;
        $args['price_list_id'] = null;
        $args['price_list_fee_id'] = null;

        return new parent($args, $account);
    }
}
