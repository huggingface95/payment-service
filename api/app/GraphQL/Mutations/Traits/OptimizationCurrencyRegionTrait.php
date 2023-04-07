<?php

namespace App\GraphQL\Mutations\Traits;

use App\DTO\GraphQLResponse\CurrencyAndRegionResponse;
use App\DTO\TransformerDTO;
use Illuminate\Support\Collection;

trait OptimizationCurrencyRegionTrait
{

    public function optimizeCurrencyRegionInput(array $input): Collection
    {
        return collect($input)
            ->map(function ($item) {
                return collect($item['currency_id'])->crossJoin(!empty($item['regions']) ? $item['regions'] : [0])->map(function ($item) {
                    return collect(['currency_id', 'region_id'])->combine($item);
                });
            })->collapse();
    }

    public function optimizeCurrencyRegionResponse(Collection $currenciesRegions): array
    {
        $result = [];
        foreach ($currenciesRegions as $k => $v) {
            $currency = $v['currency'];
            $regions = $v['regions'];

            if (($key = $this->searchTwoArrays($result, $regions)) !== false) {
                $result[$key]->currencies[] = $currency;
            } else {
                $result[] = TransformerDTO::transform(CurrencyAndRegionResponse::class, [$currency], $regions);
            }
        }

        return $result;
    }

    private function searchTwoArrays(array $array1, Collection $array2): bool|int
    {
        foreach ($array1 as $k => $v) {
            if ($array2->diff($v->regions)->count() == 0) {
                return $k;
            }
        }
        return false;
    }


}
