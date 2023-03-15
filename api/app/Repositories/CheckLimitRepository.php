<?php

namespace App\Repositories;

use App\DTO\Service\CheckLimitDTO;
use App\Enums\PaymentStatusEnum;
use App\Models\Account;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupType;
use App\Models\Members;
use App\Models\Payments;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\CheckLimitRepositoryInterface;
use Illuminate\Support\Collection;

class CheckLimitRepository implements CheckLimitRepositoryInterface
{
    public function getAllProcessedAmount(CheckLimitDTO $checkLimitDTO): Collection
    {
        return $checkLimitDTO->object instanceof TransferOutgoing ?
            $this->getAllTransferOutgoingProcessedAmount($checkLimitDTO->object)
            : $this->getAllPaymentProcessedAmount($checkLimitDTO->object);
    }

    public function getAllLimits(CheckLimitDTO $checkLimitDTO): Collection
    {
        $allLimits = collect([$checkLimitDTO->account?->limits, $checkLimitDTO->account?->commissionTemplate?->commissionTemplateLimits]);
        if ($checkLimitDTO->account?->clientable instanceof ApplicantCompany) {
            if ($checkLimitDTO->object instanceof TransferOutgoing) {
                $applicantBankingAccess = ApplicantBankingAccess::query()
                    ->where('applicant_individual_id', $checkLimitDTO->object->requested_by_id)
                    ->where('applicant_company_id', $checkLimitDTO->object->sender_id)->first();
                $allLimits = $allLimits->prepend($applicantBankingAccess);
            } else {
                $allLimits = $allLimits->prepend($checkLimitDTO->object->applicantIndividual->applicantBankingAccess);
            }
        }

        return $allLimits;
    }

    public function createReachedLimit(Account $account, $limit): void
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

    public function getAllPaymentProcessedAmount(Payments $payment): Collection
    {
        return Payments::query()
            ->where('member_id', $payment->member_id)
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::SENT->value])
            ->get()
            ->push($payment);
    }

    public function getAllTransferOutgoingProcessedAmount(TransferOutgoing $transfer): Collection
    {
        return TransferOutgoing::query()
            ->where('requested_by_id', $transfer->requested_by_id)
            ->where('user_type', class_basename(Members::class))
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::SENT->value])
            ->get()
            ->push($transfer);
    }

}
