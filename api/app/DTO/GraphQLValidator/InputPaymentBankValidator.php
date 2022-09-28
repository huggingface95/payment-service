<?php

namespace App\DTO\GraphQLValidator;

use App\Models\PaymentSystem;

class InputPaymentBankValidator
{

    public array $name;
    public array $address;
    public array $country_id;
    public array $payment_system_id;
    public array $payment_provider_id;
    public array $bank_code;
    public array $payment_system_code;


    public const DISABLE_PARAMS_IN_SEPA = ['bank_code', 'payment_system_code'];


    public static function transform(array $args): self
    {
        $isSepa = PaymentSystem::query()->where('id', $args['payment_system_id'])->where('name','SEPA')->exists();

        $dto = new self();
        $dto->name = ['required'];
        $dto->address = ['required'];
        $dto->country_id = ['required'];
        $dto->payment_system_id = ['required'];
        $dto->payment_provider_id = ['required'];
        if (!$isSepa){
            $dto->bank_code = ['required'];
            $dto->payment_system_code = ['required'];
        }

        return $dto;
    }
}
