<?php

namespace App\GraphQL\Mutations;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\PaymentJob;
use App\Models\Account;
use App\Models\AccountLimit;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\CommissionTemplateLimit;
use App\Models\CommissionTemplateLimitPeriod;
use App\Models\CommissionTemplateLimitTransferDirection;
use App\Models\CommissionTemplateLimitType;
use App\Models\GroupType;
use App\Models\Payments;
use App\Services\PaymentsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PaymentsMutator
{
    public function __construct(protected PaymentsService $paymentsService)
    {
    }

    /**
     * @throws GraphqlException
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

        $allAmount = $this->paymentsService->getAllProcessedAmount($payment);

        $allLimits = $this->getAllLimits($payment);

        if (false === $this->checkLimit($payment->account, $allLimits, $allAmount, $payment->amount)) {
            throw new GraphqlException('limit is exceeded', 'use');
        }

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

    private function checkLimit(Account $account, Collection $allLimits, Collection $allProcessedAmount, $paymentAmount): bool
    {
        foreach ($allLimits->flatten(1)->filter(function ($l) {
            return $l;
        }) as $limit) {
            if ($limit instanceof ApplicantBankingAccess) {
                if ($limit->daily_limit < $allProcessedAmount->whereBetween(
                    'created_at',
                    [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')]
                )->sum('amount')
                    || $limit->monthly_limit < $allProcessedAmount->whereBetween(
                        'created_at',
                        [Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'), Carbon::now()->endOfMonth()->format('Y-m-d H:i:s')]
                    )->sum('amount')
                    || $limit->operation_limit < $paymentAmount
                    || $limit->used_limit < $allProcessedAmount->sum('amount')
                ) {
                    $this->createReachedLimit($account, $limit);

                    return false;
                }
            } elseif ($limit instanceof AccountLimit || $limit instanceof CommissionTemplateLimit) {
                $processedAmount = $allProcessedAmount
                    ->when($limit->commissionTemplateLimitPeriod->name, function ($q, $name) {
                        if ($name == CommissionTemplateLimitPeriod::YEARLY) {
                            return $q->whereBetween(
                                'created_at',
                                [
                                    Carbon::now()->startOfYear()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfYear()->format('Y-m-d H:i:s'),
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::MONTHLY) {
                            return $q->whereBetween(
                                'created_at',
                                [
                                    Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::WEEKLY) {
                            return $q->whereBetween(
                                'created_at',
                                [
                                    Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfWeek()->format('Y-m-d H:i:s'),
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::DAILY) {
                            return $q->whereBetween(
                                'created_at',
                                [
                                    Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::EACH_TIME) {
                            return $q->whereNull('id');
                        } elseif ($name == CommissionTemplateLimitPeriod::ONE_TIME) {
                            return $q->whereNull('id');
                        }

                        return $q;
                    })
                    ->when($limit->commissionTemplateLimitTransferDirection->name, function ($q, $name) {
                        if ($name == CommissionTemplateLimitTransferDirection::INCOMING) {
                            return $q->where('type_id', 1);
                        } elseif ($name == CommissionTemplateLimitTransferDirection::OUTGOING) {
                            return $q->where('type_id', 2);
                        } elseif ($name == CommissionTemplateLimitTransferDirection::ALL) {
                            return $q;
                        }

                        return $q;
                    });

                if ($limit->commissionTemplateLimitType->name == CommissionTemplateLimitType::TRANSACTION_AMOUNT
                    && $limit->amount < $processedAmount->sum('amount')
                ) {
                    $this->createReachedLimit($account, $limit);

                    return false;
                } elseif ($limit->commissionTemplateLimitType->name == CommissionTemplateLimitType::TRANSACTION_COUNT
                    && $limit->period_count < $processedAmount->count()) {
                    $this->createReachedLimit($account, $limit);

                    return false;
                } elseif ($limit->commissionTemplateLimitType->name == CommissionTemplateLimitType::ALL
                    && $limit->amount < $processedAmount->sum('amount')
                    && $limit->period_count < $processedAmount->count()
                ) {
                    $this->createReachedLimit($account, $limit);

                    return false;
                }
            }
        }

        return true;
    }

    private function createReachedLimit(Account $account, $limit): void
    {
        $account->reachedLimits()->create([
            'group_type' => $account->clientable instanceof ApplicantIndividual ? GroupType::INDIVIDUAL : GroupType::COMPANY,
            'client_name' => $account->clientable->fullname ?? $account->clientable->name,
            'client_state' => $account->clientable->state->name,
            'transfer_direction' => $limit->commissionTemplateLimitTransferDirection->name,
            'limit_type' => $limit->commissionTemplateLimitType->name,
            'limit_value' => $limit->commissionTemplateLimitPeriod->name,
            'limit_currency' => $limit->currency->name,
            'period' => $limit->period_count,
            'amount' => $limit->amount,
        ]);
    }

    private function getAllLimits(Payments $payment): Collection
    {
        /** @var Account $account */
        $account = $payment->account()->with(['clientable', 'limits', 'commissionTemplate.commissionTemplateLimits'])->first();
        $allLimits = collect([$account->limits, $account->commissionTemplate->commissionTemplateLimits]);
        if ($account->clientable instanceof ApplicantCompany) {
            $allLimits = $allLimits->prepend($payment->applicantIndividual->applicantBankingAccess);
        }

        return $allLimits;
    }
}
