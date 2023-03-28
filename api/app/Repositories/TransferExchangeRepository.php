<?php

namespace App\Repositories;

use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransferExchangeRepository extends Repository implements TransferExchangeRepositoryInterface
{
    protected function model(): string
    {
        return TransferExchange::class;
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
