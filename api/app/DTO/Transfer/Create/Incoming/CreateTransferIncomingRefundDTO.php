<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\BeneficiaryTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\PaymentBank;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTransferIncomingRefundDTO extends CreateTransferIncomingDTO
{
    public static function transform(array $args, int $operationType): CreateTransferIncomingDTO
    {
        $date = Carbon::now();
        $account = Account::findOrFail($args['account']['id']);

        $args['company_id'] = $account->company_id;
        $args['amount_debt'] = $args['amount'];
        $args['beneficiary_type_id'] = $account->account_type == BeneficiaryTypeEnum::PERSONAL->value ? BeneficiaryTypeEnum::PERSONAL->value : BeneficiaryTypeEnum::CORPORATE->value;
        $args['requested_by_id'] = Auth::guard('api')->check() ? 1 : Auth::guard('api_client')->user()?->id;
        $args['reason'] = 'Refund #'.$args['id'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = PaymentBank::query()->where('payment_provider_id', $args['payment_provider_id'])->where('payment_system_id', $args['payment_system_id'])->first()?->id ?? throw new GraphqlException('Payment bank not found', 'use');
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = $args['recipient_country_id'];
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return new parent($args, $account);
    }
}
