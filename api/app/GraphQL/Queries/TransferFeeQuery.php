<?php

namespace App\GraphQL\Queries;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Models\Account;
use App\Models\TransferOutgoing;
use App\Services\CommissionService;
use App\Services\TransferExchangeService;
use Illuminate\Support\Str;

final class TransferFeeQuery
{
    public function __construct(
        protected CommissionService $commissionService,
        protected TransferExchangeService $transferExchangeService
    ) {
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

    public function getExchange($_, array $args): array
    {
        if (!empty($args['amount_dst'])) {
            $amount = $args['amount_dst'];
        } else {
            $amount = $args['amount'];
        }

        $fromAccount = new Account([
            'currency_id' => $args['currency_src_id'],
            'current_balance' => $amount,
        ]);

        $toAccount = new Account([
            'currency_id' => $args['currency_id_dst'],
            'current_balance' => $amount,
        ]);

        $transfer = new TransferOutgoing([
            'price_list_fee_id' => $args['price_list_fee_id'],
            'operation_type_id' => OperationTypeEnum::EXCHANGE->value,
            'currency_id' => $args['currency_src_id'],
            'urgency_id' => !empty($args['urgency_id']) ? $args['urgency_id'] : PaymentUrgencyEnum::STANDART->value,
            'amount' => $amount,
            'amount_debt' => $amount,
        ]);
        $transfer->id = 1;

        $transaction = TransformerDTO::transform(TransactionDTO::class, $transfer, $fromAccount, $toAccount);

        if (!empty($args['amount_dst'])) {
            $fees = $this->transferExchangeService->getAllExchangeCommissionsByAmountDst($args, $transfer, $transaction, $fromAccount, $toAccount);
        } else {
            $fees = $this->transferExchangeService->getAllExchangeCommissions($args, $transfer, $transaction, $fromAccount, $toAccount);
        }

        foreach ($fees as $key => $value) {
            $fees[$key] = Str::decimal($value);
        }

        return $fees;
    }
}
