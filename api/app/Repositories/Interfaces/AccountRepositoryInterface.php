<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface AccountRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function update(Model|Builder $model, array $data): Model|Builder;
}
