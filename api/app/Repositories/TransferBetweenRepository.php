<?php

namespace App\Repositories;

use App\Models\TransferBetween;
use App\Repositories\Interfaces\TransferBetweenRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransferBetweenRepository extends Repository implements TransferBetweenRepositoryInterface
{
    protected function model(): string
    {
        return TransferBetween::class;
    }

    public function findById(int $id): Model|Builder|null
    {
        return $this->find(['id' => $id]);
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
}
