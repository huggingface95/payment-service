<?php

namespace App\DTO\Payment;


use App\Models\ApplicantIndividual;


class PayerDTO
{
    public string $clientCustomerId;
    public string $walletUuid;
    public object $individual;

    public static function transform(ApplicantIndividual $applicantIndividual): PayerDTO
    {
        $dto = new self();

        $dto->clientCustomerId = "9999999";
        $dto->walletUuid = "9999999";
        $dto->individual = (object)[
            'lastName' => $applicantIndividual->first_name,
            'firstName' => $applicantIndividual->last_name,
            'email' => $applicantIndividual->email,
            'phone' => $applicantIndividual->phone,
            'address' => new \stdClass(),
            'document' => new \stdClass()
        ];

        return $dto;
    }
}
