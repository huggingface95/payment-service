<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\CommissionPriceList;
use App\Models\PriceListFee;
use App\Repositories\TransferIncomingRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTransferIncomingStandardDTO extends CreateTransferIncomingDTO
{
    public static function transform(array $args, int $operationType, TransferIncomingRepository $repository): CreateTransferIncomingDTO
    {
        $account = Account::findOrFail($args['account_id']);
        $args['company_id'] = $account->company_id;
        $args['status_id'] ??= PaymentStatusEnum::UNSIGNED->value;

        if ($args['status_id'] != PaymentStatusEnum::REFUND->value) {
            CommissionPriceList::query()
                ->where('id', $args['price_list_id'])
                ->where('company_id', $args['company_id'])
                ->first() ?? throw new GraphqlException('Commission price list not found', 'use');

            PriceListFee::query()
                ->where('id', $args['price_list_fee_id'])
                ->where('operation_type_id', $operationType)
                ->where('company_id', $args['company_id'])
                ->first() ?? throw new GraphqlException('Price list fee not found', 'use');
        }

        $date = Carbon::now();

        $args['amount_debt'] = $args['amount'];
        $args['beneficiary_type_id'] = $args['beneficiary_type'] ?? $args['beneficiary_type_id'];
        $args['urgency_id'] ??= PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['respondent_fees_id'] ??= RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];
        $args['project_id'] = $account->project_id;

        return new parent($args, $account);
    }
}
