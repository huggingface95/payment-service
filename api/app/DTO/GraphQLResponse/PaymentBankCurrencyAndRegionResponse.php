<?php

namespace App\DTO\GraphQLResponse;

class PaymentBankCurrencyAndRegionResponse
{
    public array $currency_id;
    public array $regions;

    public static function transform(array $currencyId, array $regions): self
    {
        $dto = new self();
        $dto->currency_id = $currencyId;
        $dto->regions = $regions;

        return $dto;
    }
}
