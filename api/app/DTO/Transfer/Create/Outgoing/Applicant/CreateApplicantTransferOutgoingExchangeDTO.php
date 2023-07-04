<?php

namespace App\DTO\Transfer\Create\Outgoing\Applicant;

use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\Company;
use App\Models\PriceListFee;
use App\Repositories\TransferOutgoingRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateApplicantTransferOutgoingExchangeDTO extends CreateTransferOutgoingDTO
{
    public static function transform(Account $account, string $amount, array $args): CreateTransferOutgoingDTO
    {
        $date = Carbon::now();

        $args['account_id'] = $account->id;
        $args['currency_id'] = $account->currencies?->id;
        $args['company_id'] = $account->company_id;
        $args['amount'] = $amount;
        $args['amount_debt'] = $amount;
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = PaymentUrgencyEnum::STANDART->value;
        $args['operation_type_id'] = OperationTypeEnum::EXCHANGE->value;
        $args['payment_provider_id'] = $account->company->paymentProviderInternal?->id ?? throw new GraphqlException('Internal Payment provider not found');
        $args['payment_system_id'] = $account->company->paymentProviderInternal->paymentSystemInternal?->id ?? throw new GraphqlException('Internal Payment system not found');
        $args['payment_bank_id'] = null;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::CLIENT_DASHBOARD->toString();
        $args['recipient_country_id'] = $account->clientable?->country_id ?? throw new GraphqlException('Recipient country not found');
        $args['recipient_bank_country_id'] ??= Company::findOrFail($args['company_id'])->country_id;
        $args['respondent_fees_id'] = RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;
        $args['group_id'] = $account->group_role_id;
        $args['group_type_id'] = $account->group_type_id;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $date->format('Y-m-d H:i:s');
        $args['project_id'] = $account->project_id;

        $repository = new TransferOutgoingRepository();
        $args['region_id'] = null;
        $args['price_list_id'] = $repository->getCommissionPriceListIdByGroup($args) ?? throw new GraphqlException('Commission price list not found', 'use');

        $args['price_list_fee_id'] = PriceListFee::query()
            ->where('price_list_id', $args['price_list_id'])
            ->where('operation_type_id', $args['operation_type_id'])
            ->first()?->id ?? throw new GraphqlException('Price list fee not found', 'use');

        return new parent($args, $account);
    }
}
