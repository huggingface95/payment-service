<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface TransferBetweenRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function create(array $data): Model|Builder;

    public function update(Model|Builder $model, array $data): Model|Builder;
}
