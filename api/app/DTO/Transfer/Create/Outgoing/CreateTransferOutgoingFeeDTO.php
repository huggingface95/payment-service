<?php

namespace App\DTO\Transfer\Create\Outgoing;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateTransferOutgoingFeeDTO extends CreateTransferOutgoingDTO
{
    /**
     * @throws GraphqlException
     */
    public static function transform(array $args, int $operationType): CreateTransferOutgoingDTO
    {
        $account = Account::where('id', $args['account_id'])->first() ?? throw new GraphqlException('Account not found', 'use');
        $args['company_id'] = $account->company_id;

        $date = Carbon::now()->format('Y-m-d H:i:s');
        $args['amount_debt'] = $args['amount'];
        $args['currency_id'] = $args['currency_id'] == $account->currency_id ? $args['currency_id'] : throw new GraphqlException('The account has a different currency', 'use');
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = null;
        $args['payment_number'] = Str::uuid();
        $args['system_message'] = '';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['urgency_id'] ??= PaymentUrgencyEnum::STANDART->value;
        $args['created_at'] = $date;
        $args['project_id'] = $account->project_id;

        if (!empty($args['execution_at'])) {
            $executionDate = Carbon::parse($args['execution_at'])->startOfDay();
            $createDate = Carbon::parse($date)->startOfDay();

            if ($executionDate->lte($createDate)) {
                throw new GraphqlException('Execution date cannot be earlier than tomorrow', 'use');
            }
        } else {
            $args['execution_at'] = $args['created_at'];
        }

        /* @todo: Check this fields */
        $args['recipient_bank_country_id'] ??= Company::findOrFail($args['company_id'])->country_id;
        $args['recipient_country_id'] ??= Company::findOrFail($args['company_id'])->country_id;
        $args['respondent_fees_id'] ??= RespondentFeesEnum::CHARGED_TO_CUSTOMER->value;

        return new parent($args, $account);
    }
}
