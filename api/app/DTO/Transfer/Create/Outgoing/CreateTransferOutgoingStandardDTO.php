<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\Company;
use App\Models\PriceListFee;
use App\Repositories\TransferOutgoingRepository;
use Carbon\Carbon;

class CreateTransferOutgoingStandardDTO extends CreateTransferOutgoingDTO
{
    /**
     * @throws GraphqlException
     */
    public static function transform(array $args, int $operationType, TransferOutgoingRepository $repository): CreateTransferOutgoingDTO
    {
        $account = Account::where('id', $args['account_id'])->first();
        $args['company_id'] = $account->company_id;

        $args['region_id'] = $repository->getRegionIdByArgs($args) ?? throw new GraphqlException('Region not found', 'use');
        $args['price_list_id'] = $repository->getCommissionPriceListIdByArgs($args, $account->client_type) ?? throw new GraphqlException('Commission price list not found', 'use');

        $args['price_list_fee_id'] = PriceListFee::query()
            ->where('price_list_id', $args['price_list_id'])
            ->where('operation_type_id', $operationType)
            ->first()?->id ?? throw new GraphqlException('Price list fee not found', 'use');

        $date = Carbon::now()->format('Y-m-d H:i:s');
        $args['amount_debt'] = $args['amount'];
        $args['currency_id'] = $account->currency_id;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['urgency_id'] ??= PaymentUrgencyEnum::STANDART->value;
        $args['created_at'] = $date;

        if (empty($args['recipient_bank_country_id'])) {
            $args['recipient_bank_country_id'] = Company::findOrFail($args['company_id'])->country_id;
        }

        if (!empty($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
        } else {
            $args['execution_at'] = $args['created_at'];
        }

        return new parent($args, $account);
    }
}
