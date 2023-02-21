<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AccountRepositoryInterface
{
    public function findById(int $id): Model|Builder|null;

    public function update(Model|Builder $model, array $data): Model|Builder;

    public function getAccountsByPriceListFeeScheduledId(int $priceListScheduledId);

    public function getAmountOfCashFlowForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float;

    public function getTransfersForPeriodByAccountId(int $applicantId, string $dateFrom, string $dateTo): Collection;

    public function getBalanceByAccountIdAndDate(int $accountId, string $date): float;

    public function getDebitTurnoverForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float;

    public function getCreditTurnoverForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float;
}
