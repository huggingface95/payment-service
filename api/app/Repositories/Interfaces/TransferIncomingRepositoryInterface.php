<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface TransferIncomingRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function attachFileById(Model|Builder $model, array $data): Model|Builder|null;

    public function create(array $data): Model|Builder;

    public function createWithSwift(array $data): Model|Builder;

    public function update(Model|Builder $model, array $data): Model|Builder;

    public function getCommissionPriceListIdByArgs(array $args, string $clientType): int|null;

    public function getRegionIdByArgs(array $args): int|null;
}
