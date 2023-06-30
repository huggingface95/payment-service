<?php

namespace App\GraphQL\Mutations\Traits;

use App\Models\CurrencyExchangeRate;
use App\Models\QuoteProvider;
use Illuminate\Support\Collection;

trait UpdateOrCreateCurrencyExchangeRateTrait
{

    public function updateOrCreateRate(Collection $srcDst, QuoteProvider $quoteProvider, array &$rates = []): void
    {
        $this->updateOrCreateHistory($srcDst, $quoteProvider);
        $this->updateOrCreateExchangeRate($srcDst, $quoteProvider, $rates);
    }

    protected function updateOrCreateHistory(Collection $srcDst, QuoteProvider $quoteProvider): void
    {
        $srcDst->each(function ($v) use ($quoteProvider) {
            $quoteProvider->currencyRateHistories()->updateOrCreate([
                'currency_src_id' => $v['currency_src_id'],
                'currency_dst_id' => $v['currency_dst_id'],
                'created_at' => $v['created_at'],
            ], ['rate' => $v['rate']]);
        });
    }

    protected function updateOrCreateExchangeRate(Collection $srcDst, QuoteProvider $quoteProvider, array &$rates): void
    {
        $srcDst->groupBy(['currency_src_id', 'currency_dst_id'])->map(function ($groups) use ($quoteProvider, &$rates) {
            $groups->each(function ($v) use ($quoteProvider, &$rates) {
                $v = $v->last();
                /** @var CurrencyExchangeRate $exchangeRate */
                $exchangeRate = $quoteProvider->currencyExchangeRates()->where([
                    ['currency_src_id', $v['currency_src_id']],
                    ['currency_dst_id', $v['currency_dst_id']]
                ])->first();

                if (!$exchangeRate) {
                    $rates[] = $quoteProvider->currencyExchangeRates()->create([
                        'currency_src_id' => $v['currency_src_id'],
                        'currency_dst_id' => $v['currency_dst_id'],
                        'rate' => $v['rate']
                    ]);
                } else if (!$exchangeRate->updated_at || ($exchangeRate->updated_at->timestamp < $v['created_at']->timestamp)) {
                    $exchangeRate->rate = $v['rate'];
                    $exchangeRate->save();
                    $rates[] = $exchangeRate;
                }
            });
        });
    }

}
