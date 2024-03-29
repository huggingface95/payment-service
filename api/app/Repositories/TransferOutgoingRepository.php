<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\CommissionPriceList;
use App\Models\Files;
use App\Models\Region;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransferOutgoingRepository extends Repository implements TransferOutgoingRepositoryInterface
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        parent::__construct();

        $this->fileService = $fileService;
    }

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
        if (! empty($data)) {
            $files = $model->files()->pluck('files.id')->toArray();
            $filesToAdd = array_diff($data, $files);

            $model->files()->attach($filesToAdd, ['transfer_type' => class_basename(TransferOutgoing::class)]);
        }

        return $model;
    }

    public function detachFileById(Model|Builder $model, array $data): Model|Builder|null
    {
        if (! empty($data)) {
            foreach ($data as $fileId) {
                $file = Files::find($fileId);
                if ($file) {
                    $file->delete();
                    $this->fileService->deleteFile($file);
                }
            }
        }

        return $model;
    }

    public function create(array $data): Model|Builder
    {
        return $this->query()->create($data);
    }

    public function createWithSwift(array $data): Model|Builder
    {
        $transfer = $this->query()->create($data);

        if (isset($data['transfer_swift'])) {
            $this->createSwiftModels($transfer, $data['transfer_swift']);
        }

        return $transfer;
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        return $model;
    }

    public function updateWithSwift(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        if (isset($data['transfer_swift'])) {
            $model->transferSwift()->delete();
            $this->createSwiftModels($model, $data['transfer_swift']);
        }

        return $model;
    }

    public function getWaitingExecutionDateTransfers(): Collection
    {
        return $this->query()
            ->where('status_id', PaymentStatusEnum::WAITING_EXECUTION_DATE->value)
            ->whereDate('execution_at', '<=', Carbon::today())
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

    public function getCommissionPriceListIdByArgs(array $args, string $clientType): int|null
    {
        return CommissionPriceList::query()
            ->where('company_id', '=', $args['company_id'])
            ->where('commission_template_id', '=', function ($query) use ($args, $clientType) {
                $query->select('project_settings.commission_template_id')
                    ->from('projects')
                    ->join('project_settings', 'projects.id', '=', 'project_settings.project_id')
                    ->where('projects.id', '=', $args['project_id'])
                    ->where('applicant_type', '=', $clientType);
            })
            ->where('provider_id', '=', $args['payment_provider_id'])
            ->where('payment_system_id', '=', $args['payment_system_id'])
            ->where('region_id', '=', $args['region_id'])
            ->first()?->id;
    }

    public function getCommissionPriceListIdByGroup(array $args): int|null
    {
        return CommissionPriceList::query()
            ->where('company_id', '=', $args['company_id'])
            ->where('commission_template_id', '=', function ($query) use ($args) {
                $query->select('group_role_providers.commission_template_id')
                    ->from('group_role_providers')
                    ->where('group_role_providers.group_role_id', $args['group_id'])
                    ->where('group_role_providers.payment_provider_id', $args['payment_provider_id']);
            })
            ->where('provider_id', '=', $args['payment_provider_id'])
            ->where('payment_system_id', '=', $args['payment_system_id'])
            ->where('region_id', '=', $args['region_id'])
            ->first()?->id;
    }

    public function getRegionIdByArgs(array $args): int|null
    {
        return Region::query()
            ->join('region_countries', 'regions.id', '=', 'region_countries.region_id')
            ->where('region_countries.country_id', '=', $args['recipient_bank_country_id'])
            ->where('regions.company_id', '=', $args['company_id'])
            ->first()?->id;
    }

    private function createSwiftModels(Model|Builder $model, array $swifts): void
    {
        foreach ($swifts as $swift) {
            $model->transferSwift()->create(
                array_merge(
                    $swift,
                    ['transfer_type' => class_basename(TransferOutgoing::class)]
                )
            );
        }
    }
}
