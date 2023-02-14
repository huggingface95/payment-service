<?php

namespace App\Repositories;

use App\Models\PriceListFeeScheduled;
use App\Repositories\Interfaces\PriceListFeeScheduledRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceListFeeScheduledRepository extends Repository implements PriceListFeeScheduledRepositoryInterface
{
    protected function model(): string
    {
        return PriceListFeeScheduled::class;
    }

    public function getScheduledFeesByDate(string $date): Collection
    {
        return $this->query()
            ->whereDate('starting_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereDate('end_date', '>=', $date)
                    ->orWhereNull('end_date');
            })
            ->get();
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->update($data);

        return $model;
    }
}
