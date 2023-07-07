<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface TransferExchangeRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function create(array $data): Model|Builder;

    public function update(Model|Builder $model, array $data): Model|Builder;

    public function getExchangeRate(int $priceListId, int $currencySrcId, string $currencyDstId): array;
}
