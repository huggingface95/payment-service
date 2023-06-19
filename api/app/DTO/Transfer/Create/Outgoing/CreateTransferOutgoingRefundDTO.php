<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\BeneficiaryTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTransferOutgoingRefundDTO extends CreateTransferOutgoingDTO
{
    public static function transform(array $args, int $operationType): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();
        $account = Account::findOrFail($args['account_id']);

        $args['amount_debt'] = $args['amount'];
        $args['beneficiary_type_id'] = $account->account_type == BeneficiaryTypeEnum::PERSONAL->value ? BeneficiaryTypeEnum::PERSONAL->value : BeneficiaryTypeEnum::CORPORATE->value;
        $args['requested_by_id'] = Auth::guard('api')->check() ? 1 : Auth::guard('api_client')->user()?->id;
        $args['reason'] = 'Refund #'.$args['id'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['recipient_country_id'] = $args['sender_country_id'];
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return new parent($args, $account);
    }
}
