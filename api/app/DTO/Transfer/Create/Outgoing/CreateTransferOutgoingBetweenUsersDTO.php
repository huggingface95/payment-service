<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\CommissionPriceList;
use App\Models\PriceListFee;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * @throws GraphqlException
 */
class CreateTransferOutgoingBetweenUsersDTO extends CreateTransferOutgoingDTO
{
    public static function transform(Account $fromAccount, Account $toAccount, int $operationType, array $args): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();

        CommissionPriceList::query()
            ->where('id', $args['price_list_id'])
            ->where('company_id', $fromAccount->company_id)
            ->first() ?? throw new GraphqlException('Commission price list not found', 'use');
        
        PriceListFee::query()
            ->where('id', $args['price_list_fee_id'])
            ->where('operation_type_id', $operationType)
            ->where('company_id', $fromAccount->company_id)
            ->first() ?? throw new GraphqlException('Price list fee not found', 'use');
        
        $args['account_id'] = $fromAccount->id;
        $args['currency_id'] = $fromAccount->currencies?->id;
        $args['company_id'] = $fromAccount->company_id;
        $args['amount'] = $args['amount'];
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = Str::uuid();
        $args['payment_provider_id'] = $fromAccount->company->paymentProviderInternal?->id ?? throw new GraphqlException('Internal Payment provider not found');
        $args['payment_system_id'] = $fromAccount->company->paymentSystemInternal?->id ?? throw new GraphqlException('Internal Payment system not found');
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['recipient_country_id'] = $toAccount->clientable?->country_id ?? throw new GraphqlException('Recipient country not found');
        $args['respondent_fees_id'] = $args['respondent_fee_id'] ?? RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];
        $args['recipient_bank_country_id'] = 1;

        return new parent($args, $fromAccount);
    }
}
