<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Jobs\Redis\PaymentJob;
use App\Models\AccountLimit;
use App\Models\Accounts;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\CommissionTemplateLimit;
use App\Models\CommissionTemplateLimitPeriod;
use App\Models\CommissionTemplateLimitTransferDirection;
use App\Models\CommissionTemplateLimitType;
use App\Models\Groups;
use App\Models\Payments;
use App\Models\PaymentStatus;
use Illuminate\Support\Carbon;

class PaymentsMutator
{

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @throws GraphqlException
     */

    public function create($root, array $args)
    {
        $memberId = Payments::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $args['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $payment = new Payments($args);

        if (false === $this->checkLimit($payment)) {
            throw new GraphqlException('limit is exceeded', "use");
        }

        $payment = Payments::create($args);
        dispatch(new PaymentJob($payment));

        return $payment;
    }

    public function update($_, array $args)
    {
        $payment = Payments::find($args['id']);
        $memberId = Payments::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $payment->update($args);
        return $payment;
    }

    private function checkLimit(Payments $payment): bool
    {
        /** @var Accounts $account */
        $account = $payment->Accounts()->with('clientable', 'limits', 'commissionTemplate.commissionTemplateLimits')->first();
        $allLimits = collect([$account->limits, $account->commissionTemplate->commissionTemplateLimits]);
        if ($account->clientable instanceof ApplicantCompany) {
            $allLimits = $allLimits->prepend($payment->applicantIndividual->applicantBankingAccess);
        }

        $allProcessedAmount = Payments::query()
            ->where('owner_id', $payment->owner_id)
            ->whereIn('status_id', [PaymentStatus::PENDING_ID, PaymentStatus::COMPLETED_ID])
            ->get()->push($payment);

        foreach ($allLimits->flatten(1)->filter(function ($l) {
            return $l;
        }) as $limit) {
            if ($limit instanceof ApplicantBankingAccess) {
                if ($limit->daily_limit < $allProcessedAmount->whereBetween(
                        'created_at', [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')]
                    )->sum('amount')
                    || $limit->monthly_limit < $allProcessedAmount->whereBetween(
                        'created_at', [Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'), Carbon::now()->endOfMonth()->format('Y-m-d H:i:s')]
                    )->sum('amount')
                    || $limit->operation_limit < $payment->amount
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
                                'created_at', [
                                    Carbon::now()->startOfYear()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfYear()->format('Y-m-d H:i:s')
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::MONTHLY) {
                            return $q->whereBetween(
                                'created_at', [
                                    Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfMonth()->format('Y-m-d H:i:s')
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::WEEKLY) {
                            return $q->whereBetween(
                                'created_at', [
                                    Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')
                                ]
                            );
                        } elseif ($name == CommissionTemplateLimitPeriod::DAILY) {
                            return $q->whereBetween(
                                'created_at', [
                                    Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),
                                    Carbon::now()->endOfDay()->format('Y-m-d H:i:s')
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


    private function createReachedLimit(Accounts $account, $limit)
    {
        $account->reachedLimits()->create([
            'group_type' => $account->clientable instanceof ApplicantIndividual ? Groups::INDIVIDUAL : Groups::COMPANY,
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
}
