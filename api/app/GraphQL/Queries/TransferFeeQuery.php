<?php

namespace App\GraphQL\Queries;

use App\Enums\PaymentUrgencyEnum;
use App\Models\TransferOutgoing;
use App\Services\CommissionService;
use Illuminate\Support\Str;

final class TransferFeeQuery
{
    public function __construct(protected CommissionService $commissionService)
    {
    }

    public function get($_, array $args): array
    {
        $transfer = new TransferOutgoing();
        $transfer->price_list_fee_id = $args['price_list_id'];
        $transfer->operation_type_id = $args['operation_type_id'];
        $transfer->period_id = $args['period_id'];
        $transfer->currency_id = $args['currency_id'];
        $transfer->respondent_fees_id = $args['respondent_fees_id'];
        $transfer->urgency_id = !empty($args['urgency_id']) ? $args['urgency_id'] : PaymentUrgencyEnum::STANDART->value;
        $transfer->payment_system_id = $args['payment_system_id'];
        $transfer->payment_provider_id = $args['payment_provider_id'];
        $transfer->amount = $args['amount'];

        $fee = $this->commissionService->getAllCommissions($transfer);

        foreach ($fee as $key => $value) {
            $fee[$key] = Str::decimal($value);
        }

        return $fee;
    }
}
