<?php

namespace App\DTO\GraphQLResponse;

use Illuminate\Support\Collection;

class ProjectApiSettingsResponse
{
    public Collection $payment_providers;
    public Collection $iban_providers;

    public static function transform(Collection $paymentProviders, Collection $ibanProviders): self
    {
        $dto = new self();
        $dto->payment_providers = $paymentProviders->pluck('pivot');
        $dto->iban_providers = $ibanProviders->pluck('pivot');

        return $dto;
    }
}
