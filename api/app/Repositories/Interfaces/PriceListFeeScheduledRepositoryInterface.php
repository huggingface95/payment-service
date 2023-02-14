<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PriceListFeeScheduledRepositoryInterface
{
    public function getScheduledFeesByDate(string $date): Collection;

    public function update(Model|Builder $model, array $data): Model|Builder;
}
