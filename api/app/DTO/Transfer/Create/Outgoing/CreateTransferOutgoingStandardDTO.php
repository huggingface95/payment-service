<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
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
        $countryId = Company::findOrFail($args['company_id'])->first()?->country_id;
        $priceListId = $repository->getPriceListIdByArgs($args) ?? throw new GraphqlException('Commission price list not found');
        $priceListFeeId = PriceListFee::query()
            ->where('price_list_id', '=', $priceListId)
            ->where('operation_type_id', '=', $operationType)
            ->first()?->id ?? throw new GraphqlException('Price list fee not found');

        $date = Carbon::now();
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['reason'] = 'test';
        $args['recipient_country_id'] = $countryId;
        $args['recipient_bank_country_id'] = $countryId;
        $args['price_list_id'] = $priceListId;
        $args['price_list_fee_id'] = $priceListFeeId;
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');

        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
        } else {
            $args['execution_at'] = $args['created_at'];
        }

        return new parent($args, Account::findOrFail($args['account_id']));
    }

}
