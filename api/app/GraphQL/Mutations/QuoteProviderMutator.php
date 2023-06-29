<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\UpdateOrCreateCurrencyExchangeRateTrait;
use App\Models\Currencies;
use App\Models\QuoteProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuoteProviderMutator extends BaseMutator
{
    use UpdateOrCreateCurrencyExchangeRateTrait;

    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): QuoteProvider
    {
        try {
            DB::beginTransaction();
            /** @var QuoteProvider $quoteProvider */
            $quoteProvider = QuoteProvider::query()->create($args);
            if (isset($args['currency_src_id']) and isset($args['currency_dst_id']) and isset($args['rate'])) {
                $srcDst = $this->toSrcDstFormat($args);
                $this->updateOrCreateRate($srcDst, $quoteProvider);
            }
            DB::commit();

            return $quoteProvider;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): QuoteProvider
    {
        try {
            DB::beginTransaction();
            /** @var QuoteProvider $quoteProvider */
            $quoteProvider = QuoteProvider::query()->where('id', $args['id'])->first();
            $quoteProvider->fill($args)->save();
            if (isset($args['currency_src_id']) and isset($args['currency_dst_id']) and isset($args['rate'])) {
                $srcDst = $this->toSrcDstFormat($args);
                $this->updateOrCreateRate($srcDst, $quoteProvider);
            }
            DB::commit();

            return $quoteProvider;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    private function toSrcDstFormat(array $args): Collection
    {
        $time = Carbon::now();
        if (is_array($args['currency_dst_id']) and !empty($args['currency_dst_id'])) {
            $srcDst = collect([$args['currency_src_id']])->crossJoin($args['currency_dst_id']);
        } elseif (is_string($args['currency_dst_id']) && $args['currency_dst_id'] == 'all') {
            $srcDst = collect([$args['currency_src_id']])->crossJoin(Currencies::query()->where('id', '<>', $args['currency_src_id'])->pluck('id'));
        } else {
            throw new GraphqlException('correct currency_dst_id field', 'use', 400);
        }

        return $srcDst->map(function ($item) use ($args, $time) {
            return [
                'currency_src_id' => $item[0],
                'currency_dst_id' => $item[1],
                'created_at' => $time,
                'rate' => $args['rate']
            ];
        })->sortBy('created_at');

    }
}
