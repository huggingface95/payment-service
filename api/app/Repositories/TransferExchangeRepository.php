<?php

namespace App\Repositories;

use App\Exceptions\GraphqlException;
use App\Models\PriceListFee;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

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

    /**
     * @throws GraphqlException
     */
    public function getExchangeRate(int $priceListId, int $currencySrcId, string $currencyDstId): array
    {
        $quoteProvider = PriceListFee::find($priceListId)?->quoteProvider ??
            throw new GraphqlException('Quote provider not found. Please setup quote provider', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);

        $exchangeRate = $quoteProvider->currencyExchangeRates
            ->where('currency_src_id', $currencySrcId)
            ->where('currency_dst_id', $currencyDstId)
            ->first()?->rate;

        if ($exchangeRate === null) {
            throw new GraphqlException('Exchange rate not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $finalExchangeRate = $exchangeRate;
        if ($quoteProvider->margin_commission !== null) {
            $finalExchangeRate = $exchangeRate * (1 - $quoteProvider->margin_commission / 100);
        }

        return [
            'rate' => $exchangeRate,
            'final_rate' => $finalExchangeRate,
        ];
    }

}
