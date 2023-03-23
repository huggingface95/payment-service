<?php

namespace App\GraphQL\Mutations\Traits;

use Illuminate\Support\Collection;

trait OptimizationCurrencyRegionTrait
{

    public function optimizeCurrencyRegionInput(array $input): Collection
    {
        return collect($input)
            ->map(function ($item) {
                return collect($item['currency_id'])->crossJoin($item['regions'])->map(function ($item) {
                    return collect(['currency_id', 'region_id'])->combine($item);
                });
            })->collapse();
    }

}
