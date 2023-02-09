<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransferOutgoingRepository extends Repository implements TransferOutgoingRepositoryInterface
{
    protected function model(): string
    {
        return TransferOutgoing::class;
    }

    public function findById(int $id): Model|Builder|null
    {
        return $this->find(['id' => $id]);
    }

    public function attachFileById(Model|Builder $model, array $data): Model|Builder|null
    {
        if (isset($data)) {
            $model->files()->detach();
            $model->files()->attach(
                $data,
                ['transfer_type' => class_basename(TransferOutgoing::class)]
            );
        }

        return $model;
    }

    public function create(array $data): Model|Builder
    {
        return $this->query()->create($data);
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        return $model;
    }

    public function getWaitingExecutionDateTransfers(): Collection
    {
        return $this->query()
            ->where('status_id', PaymentStatusEnum::WAITING_EXECUTION_DATE->value)
            ->whereDate('execution_at', Carbon::today())
            ->get();
    }

    public function getSumOfDailySentTransfersByApplicantIndividualId(int $applicantId): float
    {
        return (float) $this->query()
            ->join('applicant_banking_access', function ($join) {
                $join->on('applicant_banking_access.applicant_company_id', '=', 'transfer_outgoings.sender_id')
                    ->where('transfer_outgoings.sender_type', '=', class_basename(ApplicantCompany::class));
            })
            ->where('transfer_outgoings.status_id', PaymentStatusEnum::SENT->value)
            ->whereDate('transfer_outgoings.execution_at', Carbon::today())
            ->where('transfer_outgoings.requested_by_id', $applicantId)
            ->where('transfer_outgoings.user_type', class_basename(ApplicantIndividual::class))
            ->sum('amount_debt');
    }

    public function getSumOfMonthlySentTransfersByApplicantIndividualId(int $applicantId): float
    {
        return (float) $this->query()
            ->join('applicant_banking_access', function ($join) {
                $join->on('applicant_banking_access.applicant_company_id', '=', 'transfer_outgoings.sender_id')
                    ->where('transfer_outgoings.sender_type', '=', class_basename(ApplicantCompany::class));
            })
            ->where('transfer_outgoings.status_id', PaymentStatusEnum::SENT->value)
            ->whereMonth('transfer_outgoings.execution_at', Carbon::today()->month)
            ->where('transfer_outgoings.requested_by_id', $applicantId)
            ->where('transfer_outgoings.user_type', class_basename(ApplicantIndividual::class))
            ->sum('amount_debt');
    }
}
