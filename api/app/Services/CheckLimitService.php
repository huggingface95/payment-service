<?php

namespace App\Services;

use App\DTO\Email\Request\EmailAccountMinMaxBalanceLimitRequestDTO;
use App\DTO\Service\CheckLimitDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\AccountLimit;
use App\Models\AccountState;
use App\Models\ApplicantBankingAccess;
use App\Models\CommissionTemplateLimit;
use App\Models\CommissionTemplateLimitPeriod;
use App\Models\CommissionTemplateLimitTransferDirection;
use App\Models\CommissionTemplateLimitType;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\CheckLimitRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class CheckLimitService
{
    public function __construct(
        protected CheckLimitRepositoryInterface $repository,
        protected EmailService $emailService
    ) {
    }

    /**
     * @throws GraphqlException
     * @throws EmailException
     */
    public function checkLimits(CheckLimitDTO $checkLimitDTO): void
    {
        $allAmount = $this->repository->getAllProcessedAmount($checkLimitDTO);
        $allLimits = $this->repository->getAllLimits($checkLimitDTO);

        if (false === $this->checkAccountBalanceLimit($checkLimitDTO->account, $checkLimitDTO->amount)) {
            throw new GraphqlException('balance limit error', 'use');
        }

        if (false === $this->checkLimit($checkLimitDTO->account, $allLimits, $allAmount, $checkLimitDTO->amount)) {
            throw new GraphqlException('limit is exceeded', 'use');
        }
    }

    public function checkLimit(Account $account, Collection $allLimits, Collection $allProcessedAmount, $paymentAmount): bool
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
                    $this->repository->createReachedLimit($account, $limit);

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
                    $this->repository->createReachedLimit($account, $limit);

                    return false;
                } elseif ($limit->commissionTemplateLimitType->name == CommissionTemplateLimitType::TRANSACTION_COUNT
                    && $limit->period_count < $processedAmount->count()) {
                    $this->repository->createReachedLimit($account, $limit);

                    return false;
                } elseif ($limit->commissionTemplateLimitType->name == CommissionTemplateLimitType::ALL
                    && $limit->amount < $processedAmount->sum('amount')
                    && $limit->period_count < $processedAmount->count()
                ) {
                    $this->repository->createReachedLimit($account, $limit);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @throws EmailException
     */
    public function checkAccountBalanceLimit(Account $account, $amount): bool
    {
        $calculationBalance = $account->current_balance - $amount;

        if (($isMinLimit = $account->min_limit_balance > $calculationBalance)
            || $account->max_limit_balance < $calculationBalance
        ) {
            $account->account_state_id = AccountState::SUSPENDED;
            $account->save();

            $iDto = TransformerDTO::transform(EmailAccountMinMaxBalanceLimitRequestDTO::class, $account, $isMinLimit);
            $this->emailService->sendAccountBalanceLimitDto($iDto);

            $mDto = TransformerDTO::transform(EmailAccountMinMaxBalanceLimitRequestDTO::class, $account, $isMinLimit, EmailAccountMinMaxBalanceLimitRequestDTO::MEMBER);
            $this->emailService->sendAccountBalanceLimitDto($mDto);

            return false;
        }

        return true;
    }

    /**
     * @throws GraphqlException
     */
    public function checkApplicantBankingAccessUsedLimits(TransferOutgoing $transfer): void
    {
        /** @var ApplicantBankingAccess $applicantBankingAccess */
        $applicantBankingAccess = ApplicantBankingAccess::query()->where('applicant_individual_id', $transfer->requested_by_id)
            ->where('applicant_company_id', $transfer->sender_id)
            ->first();

        $dailyLimit = $applicantBankingAccess->daily_limit - $applicantBankingAccess->used_daily_limit;
        $monthlyLimit = $applicantBankingAccess->monthly_limit - $applicantBankingAccess->used_monthly_limit;

        if ($dailyLimit < $transfer->amount_debt) {
            throw new GraphqlException('Daily limit reached', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($monthlyLimit < $transfer->amount_debt) {
            throw new GraphqlException('Monthly limit reached', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
