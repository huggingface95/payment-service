<?php

namespace App\Repositories;

use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransferIncomingRepository extends Repository implements TransferIncomingRepositoryInterface
{
    protected function model(): string
    {
        return TransferIncoming::class;
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
                ['transfer_type' => class_basename(TransferIncoming::class)]
            );
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
            $transfer->transferSwift()->create(
                array_merge(
                    $data['transfer_swift'],
                    ['transfer_type' => class_basename(TransferIncoming::class)]
                )
            );
        }
        
        return $transfer;
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        return $model;
    }
}
