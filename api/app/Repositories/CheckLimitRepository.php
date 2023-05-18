<?php

namespace App\Repositories;

use App\DTO\Service\CheckLimitDTO;
use App\Enums\ApplicantTypeEnum;
use App\Enums\ClientTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Account;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantIndividual;
use App\Models\GroupType;
use App\Models\Members;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\CheckLimitRepositoryInterface;
use Illuminate\Support\Collection;

class CheckLimitRepository implements CheckLimitRepositoryInterface
{
    public function getAllProcessedAmount(CheckLimitDTO $checkLimitDTO): Collection
    {
        return $this->getAllTransferOutgoingProcessedAmount($checkLimitDTO->object, $checkLimitDTO->clientType)
            ->merge($this->getAllTransferIncomingProcessedAmount($checkLimitDTO->object, $checkLimitDTO->clientType));
    }

    public function getAllLimits(CheckLimitDTO $checkLimitDTO): Collection
    {
        $allLimits = collect([$checkLimitDTO->account?->limits, $checkLimitDTO->account?->commissionTemplate?->commissionTemplateLimits]);
        if ($checkLimitDTO->clientType == ClientTypeEnum::APPLICANT->toString()) {
            $applicantBankingAccess = ApplicantBankingAccess::query()
                ->where('applicant_individual_id', $checkLimitDTO->object->requested_by_id)
                ->where('applicant_company_id', $checkLimitDTO->object->sender_id)->first();
            $allLimits = $allLimits->prepend($applicantBankingAccess);
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

    public function getAllTransferOutgoingProcessedAmount(TransferOutgoing $transfer, string $clientType): Collection
    {
        return TransferOutgoing::query()
            ->where('requested_by_id', $transfer->requested_by_id)
            ->where('user_type', class_basename($clientType == ClientTypeEnum::MEMBER->toString() ? Members::class : ApplicantIndividual::class))
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::SENT->value])
            ->get()
            ->push($transfer);
    }

    public function getAllTransferIncomingProcessedAmount(TransferOutgoing $transfer, string $clientType): Collection
    {
        return $clientType == ClientTypeEnum::MEMBER->toString() ? collect() : TransferIncoming::query()
            ->where('recipient_id', $transfer->requested_by_id)
            ->where('recipient_type', ApplicantTypeEnum::INDIVIDUAL->toString())
            ->whereIn('status_id', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::SENT->value])
            ->get();
    }
}
