<?php

namespace App\Repositories;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
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
                $data, ['transfer_type' => class_basename(TransferOutgoing::class)]
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
}
