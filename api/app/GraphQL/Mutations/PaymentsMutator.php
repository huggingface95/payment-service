<?php

namespace App\GraphQL\Mutations;

use App\DTO\Service\CheckLimitDTO;
use App\DTO\TransformerDTO;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\PaymentJob;
use App\Models\Payments;
use App\Services\CheckLimitService;
use App\Services\PaymentsService;
use Illuminate\Support\Carbon;

class PaymentsMutator
{
    public function __construct(protected PaymentsService $paymentsService, protected CheckLimitService $checkLimitService)
    {
    }

    /**
     * @throws GraphqlException
     * @throws EmailException
     */
    public function create($_, array $args): Payments
    {
        $date = Carbon::now();
        $args['member_id'] = auth()->user()->id;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['status_id'] = PaymentStatusEnum::PENDING->value;
        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
            $args['status_id'] = PaymentStatusEnum::WAITING_EXECUTION_DATE->value;
        }

        $payment = new Payments($args);

        $this->checkLimitService->checkLimits(TransformerDTO::transform(CheckLimitDTO::class, $payment, $args['amount']));

        $payment = $this->paymentsService->commissionCalculation($payment);

        $payment->save();

        dispatch(new PaymentJob($payment));

        return $payment;
    }

    public function update($_, array $args)
    {
        $payment = Payments::find($args['id']);
        $date = Carbon::now();

        if ($payment->status_id == PaymentStatusEnum::WAITING_EXECUTION_DATE->value && $date->lt($payment->execution_at)) {
            throw new GraphqlException('Waiting execution date', 'use');
        }

        $args['member_id'] = auth()->user()->id;
        $payment->update($args);

        return $payment;
    }
}
