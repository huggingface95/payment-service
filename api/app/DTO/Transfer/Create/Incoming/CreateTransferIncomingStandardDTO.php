<?php

namespace App\DTO\Transfer\Create\Incoming;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Models\Account;
use App\Models\PriceListFee;
use App\Repositories\TransferIncomingRepository;
use Carbon\Carbon;

class CreateTransferIncomingStandardDTO extends CreateTransferIncomingDTO
{
    public static function transform(array $args, int $operationType, TransferIncomingRepository $repository): CreateTransferIncomingDTO
    {
        $account = Account::findOrFail($args['account_id']);
        $args['company_id'] = $account->company_id;

        $args['price_list_id'] ??= $repository->getPriceListIdByArgs($args, $account->client_type);

        $args['price_list_fee_id'] ??= PriceListFee::query()
            ->where('price_list_id', '=', $args['price_list_id'])
            ->where('operation_type_id', '=', $operationType)
            ->first()?->id;

        $date = Carbon::now();

        $args['amount_debt'] = $args['amount'];
        $args['beneficiary_type_id'] = $args['beneficiary_type'] ?? $args['beneficiary_type_id'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['sender_country_id'] = 1;
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return new parent($args, $account);
    }
}
