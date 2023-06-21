<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\CommissionPriceList;
use App\Models\Company;
use App\Models\PaymentBank;
use App\Models\PriceListFee;
use App\Repositories\TransferOutgoingRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTransferOutgoingStandardDTO extends CreateTransferOutgoingDTO
{
    /**
     * @throws GraphqlException
     */
    public static function transform(array $args, int $operationType, TransferOutgoingRepository $repository): CreateTransferOutgoingDTO
    {
        $account = Account::where('id', $args['account_id'])->first() ?? throw new GraphqlException('Account not found', 'use');
        $args['company_id'] = $account->company_id;

        if (empty($args['price_list_id'])) {
            $args['region_id'] = $repository->getRegionIdByArgs($args) ?? throw new GraphqlException('out Region not found', 'use');
            $args['price_list_id'] = $repository->getCommissionPriceListIdByArgs($args, $account->client_type) ?? throw new GraphqlException('Commission price list not found', 'use');
        } else {
            CommissionPriceList::query()
                ->where('id', $args['price_list_id'])
                ->where('company_id', $args['company_id'])
                ->first() ?? throw new GraphqlException('Commission price list not found', 'use');
        }

        if (empty($args['price_list_fee_id'])) {
            $args['price_list_fee_id'] = PriceListFee::query()
                ->where('price_list_id', $args['price_list_id'])
                ->where('operation_type_id', $operationType)
                ->first()?->id ?? throw new GraphqlException('Price list fee not found', 'use');
        } else {
            PriceListFee::query()
                ->where('id', $args['price_list_fee_id'])
                ->where('operation_type_id', $operationType)
                ->where('company_id', $args['company_id'])
                ->first() ?? throw new GraphqlException('Price list fee not found', 'use');
        }

        $date = Carbon::now()->format('Y-m-d H:i:s');
        $args['amount_debt'] = $args['amount'];
        $args['currency_id'] = $account->currency_id;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = PaymentBank::query()->where('payment_provider_id', $args['payment_provider_id'])->where('payment_system_id', $args['payment_system_id'])->first()?->id ?? throw new GraphqlException('Payment bank not found', 'use');
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['urgency_id'] ??= PaymentUrgencyEnum::STANDART->value;
        $args['created_at'] = $date;
        $args['recipient_bank_country_id'] ??= Company::findOrFail($args['company_id'])->country_id;

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
