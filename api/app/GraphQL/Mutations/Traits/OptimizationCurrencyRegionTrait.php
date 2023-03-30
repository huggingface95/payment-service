<?php

namespace App\GraphQL\Mutations\Traits;

use App\DTO\GraphQLResponse\PaymentBankCurrencyAndRegionResponse;
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

    public function optimizeCurrencyRegionResponse(array $currenciesRegions): array
    {
        $result = [];
        foreach ($currenciesRegions as $k => $v) {
            if (($key = $this->searchTwoArrays($result, $v)) !== false) {
                $result[$key]->currency_id[] = $k;
            } else {
                $result[] = TransformerDTO::transform(PaymentBankCurrencyAndRegionResponse::class, [$k], $v);
            }
        }

        return $result;
    }

    private function searchTwoArrays(array $array1, array $array2): bool|int
    {
        foreach ($array1 as $k => $v) {
            if (count(array_diff($array2, $v->regions)) == 0) {
                return $k;
            }
        }
        return false;
    }


}
