<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AccountRepository extends Repository implements AccountRepositoryInterface
{
    protected function model(): string
    {
        return Account::class;
    }

    public function findById(int $id): Model|Builder|null
    {
        return $this->find(['id' => $id]);
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->updateQuietly($data);

        return $model;
    }
}
