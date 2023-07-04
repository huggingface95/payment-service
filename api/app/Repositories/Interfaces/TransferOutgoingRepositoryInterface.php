<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TransferOutgoingRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function attachFileById(Model|Builder $model, array $data): Model|Builder|null;

    public function create(array $data): Model|Builder;

    public function createWithSwift(array $data): Model|Builder;

    public function update(Model|Builder $model, array $data): Model|Builder;

    public function getWaitingExecutionDateTransfers(): Collection;

    public function getSumOfDailySentTransfersByApplicantIndividualId(int $applicantId): float;

    public function getSumOfMonthlySentTransfersByApplicantIndividualId(int $applicantId): float;

    public function getCommissionPriceListIdByArgs(array $args, string $clientType): int|null;

    public function getCommissionPriceListIdByGroup(array $args): int|null;

    public function getRegionIdByArgs(array $args): int|null;
}
