<?php

namespace App\DTO\GraphQLResponse;

use Illuminate\Support\Collection;

class CurrencyAndRegionResponse
{
    public array $currencies;
    public Collection $regions;

    public static function transform(array $currencies, Collection $regions): self
    {
        $dto = new self();
        $dto->currencies = $currencies;
        $dto->regions = $regions;

        return $dto;
    }
}
