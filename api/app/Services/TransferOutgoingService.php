<?php

namespace App\Services;

use App\Enums\FeeModeEnum;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\RespondentFeesEnum;
use App\Enums\TransferOutgoingChannelEnum;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\TransferOutgoingJob;
use App\Models\Account;
use App\Models\AccountLimit;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\CommissionTemplateLimit;
use App\Models\CommissionTemplateLimitPeriod;
use App\Models\CommissionTemplateLimitTransferDirection;
use App\Models\CommissionTemplateLimitType;
use App\Models\Fee;
use App\Models\GroupType;
use App\Models\PriceListFeeCurrency;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransferOutgoingService extends AbstractService
{
    public function __construct(
        protected AccountService $accountService,
        protected TransferOutgoingRepositoryInterface $transferRepository
    ) {
    }

    public function getAllProcessedAmount(TransferOutgoing $transfer): Collection
    {
        return TransferOutgoing::query()
            ->where('requested_by_id', $transfer->requested_by_id)
            ->where('user_type', class_basename(Members::class))
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::SENT->value])
            ->get()
            ->push($transfer);
    }

    public function getTransferAmountDebt(TransferOutgoing $transfer, float $paymentFee): ?float
    {
        return match((int) $transfer->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $transfer->amount,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $transfer->amount + $paymentFee,
            RespondentFeesEnum::SHARED_FEES->value => $transfer->amount + $paymentFee / 2,

            default=> throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    public function getAccountAmountRealWithCommission(TransferOutgoing $transfer, float $paymentFee): ?float
    {
        return match((int) $transfer->respondent_fees_id) {
            RespondentFeesEnum::CHARGED_TO_CUSTOMER->value => $transfer->amount + $paymentFee,
            RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value => $transfer->amount,
            RespondentFeesEnum::SHARED_FEES->value => $transfer->amount + $paymentFee / 2,

            default=> throw new GraphqlException('Unknown respondent fee', 'use'),
        };
    }

    public function commissionCalculation(TransferOutgoing $transfer): float
    {
        $amountDebt = 0;
        $paymentFee = 0;

        $priceListFees = PriceListFeeCurrency::where('price_list_fee_id', $transfer->price_list_fee_id)
            ->where('currency_id', $transfer->currency_id)
            ->get();

        foreach ($priceListFees as $listFee) {
            $paymentFee += $this->getFee($listFee->fee, $transfer->amount);
        }

        $amountDebt = $this->getTransferAmountDebt($transfer, $paymentFee);

        $this->createFee($transfer, $paymentFee);

        return (float) $amountDebt;
    }

    public function createFee(TransferOutgoing $transfer, float $paymentFee): void
    {
        // TODO: set fee_pp commission
        Fee::updateOrCreate(
            [
                'transfer_id' => $transfer->id,
                'transfer_type' => FeeTransferTypeEnum::OUTGOING->toString(),
            ],
            [
                'fee' => $paymentFee,
                'fee_pp' => 0,
                'fee_type_id' => 1,
                'operation_type_id' => $transfer->operation_type_id,
                'member_id' => null,
                'status_id' => $transfer->status_id,
                'client_id' => 1,
                'client_type' => class_basename(ApplicantCompany::class),
                'account_id' => $transfer->account_id,
                'price_list_fee_id' => $transfer->price_list_fee_id,
            ]
        );
    }

    public function getFee(Collection $list, $amount): ?float
    {
        $fee = $list->toArray();
        $modeKey = array_search(FeeModeEnum::RANGE->toString(), array_column($fee, 'mode'));
        if ($modeKey !== null && $modeKey !== false) {
            return self::getFeeByRangeMode($fee, $modeKey, $amount);
        } else {
            return self::getFeeByFixMode($fee, $amount);
        }

        return null;
    }

    private static function getFeeByFixMode(array $data, float $amount): ?float
    {
        return collect($data)->map(function ($fee) use ($amount) {
            return self::getConstantFee($fee, $amount);
        })->sum();

        return null;
    }

    private static function getFeeByRangeMode(array $data, int $modeKey, float $amount): ?float
    {
        $fees = null;
        if ((float) $data[$modeKey]['amount_from'] <= $amount && $amount <= (float) $data[$modeKey]['amount_to']) {
            unset($data[$modeKey]);

            foreach ($data as $fee) {
                $fees += self::getConstantFee($fee, $amount);
            }
        }

        return $fees;
    }

    public static function getConstantFee(array $data, float $amount): ?float
    {
        if ($data['mode'] == FeeModeEnum::FIX->toString()) {
            return $data['fee'];
        } elseif ($data['mode'] == FeeModeEnum::PERCENT->toString()) {
            return ($data['percent'] / 100) * $amount;
        }

        return null;
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

    public function getAllLimits(TransferOutgoing $transfer): Collection
    {
        /** @var Account $account */
        $account = $transfer->account()->with(['clientable', 'limits', 'commissionTemplate.commissionTemplateLimits'])->first();
        $allLimits = collect([$account->limits, $account->commissionTemplate->commissionTemplateLimits]);
        if ($account->clientable instanceof ApplicantCompany) {
            $allLimits = $allLimits->prepend($transfer->applicantIndividual->applicantBankingAccess);
        }

        return $allLimits;
    }

    public function checkApplicantBankingAccessUsedLimits(TransferOutgoing $transfer): void
    {
        $applicantBankingAccess = ApplicantBankingAccess::where('applicant_individual_id', $transfer->requested_by_id)
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

    public function updateApplicantBanckingAccessUsedLimits(TransferOutgoing $transaction): void
    {
        $usedMonthly = $this->transferRepository->getSumOfMonthlySentTransfersByApplicantIndividualId($transaction->requested_by_id);
        $usedDaily = $this->transferRepository->getSumOfDailySentTransfersByApplicantIndividualId($transaction->requested_by_id);

        $applicantBankingAccess = ApplicantBankingAccess::where('applicant_individual_id', $transaction->requested_by_id)
            ->where('applicant_company_id', $transaction->sender_id)
            ->first();

        if ($applicantBankingAccess) {
            $applicantBankingAccess->update([
                'used_monthly_limit' => $usedMonthly,
                'used_daily_limit' => $usedDaily,
            ]);
        }
    }

    public function validateUpdateTransferStatus(TransferOutgoing $transfer, array $args): void
    {
        $date = Carbon::today();

        switch ($transfer->status_id) {
            case PaymentStatusEnum::WAITING_EXECUTION_DATE->value:
                $executionAt = Carbon::parse($transfer->execution_at)->format('Y-m-d');
                if ($date->lt($executionAt)) {
                    if ($args['status_id'] == PaymentStatusEnum::SENT->value) {
                        throw new GraphqlException('The execution date has not yet arrived, please change the execution date', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    throw new GraphqlException('Waiting execution date', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::PENDING->value:
                if ($args['status_id'] != PaymentStatusEnum::SENT->value && $args['status_id'] != PaymentStatusEnum::CANCELED->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Pending status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::SENT->value:
                if ($args['status_id'] != PaymentStatusEnum::CANCELED->value && $args['status_id'] != PaymentStatusEnum::ERROR->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Sent status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::ERROR->value:
                if ($args['status_id'] != PaymentStatusEnum::PENDING->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Error status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::CANCELED->value:
                throw new GraphqlException('Transfer has final status which is Canceled', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);

                break;
        }
    }

    public function updateTransferStatusToSent(TransferOutgoing $transfer): void
    {
        $allAmount = $this->getAllProcessedAmount($transfer);
        $allLimits = $this->getAllLimits($transfer);

        if (false === $this->checkLimit($transfer->account, $allLimits, $allAmount, $transfer->amount)) {
            throw new GraphqlException('limit is exceeded', 'use');
        }

        DB::transaction(function () use ($transfer) {
            $amountDebt = $this->commissionCalculation($transfer);

            $this->checkApplicantBankingAccessUsedLimits($transfer);

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::SENT->value,
                'amount_debt' => $amountDebt,
            ]);

            $this->accountService->setAmmountReserveOnAccountBalance($transfer);

            $this->updateApplicantBanckingAccessUsedLimits($transfer);

            dispatch(new TransferOutgoingJob($transfer))->afterCommit();
        });
    }

    public function updateTransferStatusToCancelOrError(TransferOutgoing $transfer, int $status): void
    {
        DB::transaction(function () use ($transfer, $status) {
            $this->transferRepository->update($transfer, ['status_id' => $status]);

            $this->accountService->unsetAmmountReserveOnAccountBalance($transfer);

            $this->updateApplicantBanckingAccessUsedLimits($transfer);
        });
    }

    public function createTransfer(array $args): TransferOutgoing
    {
        $date = Carbon::now();

        $args['user_type'] = class_basename(Members::class);
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::PENDING->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = OperationTypeEnum::OUTGOING_TRANSFER->value;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['sender_id'] = 1;
        $args['sender_type'] = class_basename(ApplicantCompany::class);
        $args['system_message'] = 'test';
        $args['channel'] = TransferOutgoingChannelEnum::BACK_OFFICE->toString();
        $args['reason'] = 'test';
        $args['recipient_country_id'] = 1;
        $args['respondent_fees_id'] = 1;
        $args['created_at'] = $date->format('Y-m-d H:i:s');

        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
            $args['status_id'] = PaymentStatusEnum::WAITING_EXECUTION_DATE->value;
        } else {
            $args['execution_at'] = $date->format('Y-m-d H:i:s');
        }

        return $this->transferRepository->create($args);
    }
}
