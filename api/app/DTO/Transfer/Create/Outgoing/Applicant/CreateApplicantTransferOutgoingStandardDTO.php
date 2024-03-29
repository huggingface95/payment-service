<?php

namespace App\DTO\Transfer\Create\Outgoing\Applicant;

use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingDTO;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentSystemTypeEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\Company;
use App\Models\PaymentBank;
use App\Models\PriceListFee;
use App\Repositories\TransferOutgoingRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateApplicantTransferOutgoingStandardDTO extends CreateTransferOutgoingDTO
{
    /**
     * @throws GraphqlException
     */
    public static function transform(array $args, int $operationType, TransferOutgoingRepository $repository): CreateTransferOutgoingDTO
    {
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $account = Account::where('id', $args['account_id'])->first() ?? throw new GraphqlException('Account not found', 'use');

        $projectSettings = $account->project?->projectSettings->where('applicant_type', $account->client_type)->first();
        $args['status_id'] ??= PaymentStatusEnum::UNSIGNED->value;

        $psType = null;
        if ($args['status_id'] != PaymentStatusEnum::REFUND->value) {
            $psType = $args['payment_system_type'] == PaymentSystemTypeEnum::SEPA->value ? PaymentSystemTypeEnum::SEPA->value : PaymentSystemTypeEnum::SWIFT->value;
            $args['payment_system_id'] = $projectSettings?->paymentProvider?->paymentSystems?->where('is_active', true)->filter(function ($item) use ($psType) {
                return str_contains(strtolower($item['name']), strtolower($psType));
            })->first()?->id ?? throw new GraphqlException('Payment system not found. Payment system name must contain \'' . $psType . '\'', 'use');

            $args['payment_provider_id'] = $projectSettings?->payment_provider_id ?? throw new GraphqlException('Payment provider not found', 'use');
            $args['payment_bank_id'] = PaymentBank::query()->where('payment_provider_id', $args['payment_provider_id'])->where('payment_system_id', $args['payment_system_id'])->first()?->id ?? throw new GraphqlException('Payment bank not found', 'use');
        }

        $args['company_id'] = $account->company_id;
        $args['amount_debt'] = $args['amount'];
        $args['currency_id'] = $account->currency_id;
        $args['operation_type_id'] = $operationType;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::CLIENT_DASHBOARD->toString();
        $args['urgency_id'] = $psType == PaymentSystemTypeEnum::SEPA->value
            ? PaymentUrgencyEnum::STANDART->value
            : ($args['urgency_id'] ?? PaymentUrgencyEnum::STANDART->value);
        $args['created_at'] = $date;
        $args['recipient_bank_country_id'] ??= Company::findOrFail($args['company_id'])->country_id;
        $args['respondent_fees_id'] = $psType == PaymentSystemTypeEnum::SEPA->value
            ? RespondentFeesEnum::CHARGED_TO_CUSTOMER->value
            : $args['respondent_fees_id'];
        $args['project_id'] = $account->project_id;
        $args['group_id'] = $account->group_role_id;
        $args['group_type_id'] = $account->group_type_id;

        if ($args['status_id'] != PaymentStatusEnum::REFUND->value) {
            $args['region_id'] = $repository->getRegionIdByArgs($args) ?? throw new GraphqlException('Region not found', 'use');
            $args['price_list_id'] = $repository->getCommissionPriceListIdByArgs($args, $account->client_type) ?? throw new GraphqlException('Commission price list not found', 'use');

            $args['price_list_fee_id'] = PriceListFee::query()
                ->where('price_list_id', $args['price_list_id'])
                ->where('operation_type_id', $operationType)
                ->first()?->id ?? throw new GraphqlException('Price list fee not found', 'use');
        }

        if (!empty($args['execution_at'])) {
            $executionDate = Carbon::parse($args['execution_at'])->startOfDay();
            $createDate = Carbon::parse($date)->startOfDay();

            if ($executionDate->lte($createDate)) {
                throw new GraphqlException('Execution date cannot be earlier than tomorrow', 'use');
            }
        } else {
            $args['execution_at'] = $args['created_at'];
        }

        return new parent($args, $account);
    }
}
