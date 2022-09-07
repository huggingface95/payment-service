<?php

namespace App\DTO\GraphQLResponse;

class AccountGenerateIbanResponse
{
    private const successStatus = 'Success';

    private const errorStatus = 'Error';

    private const successMessage = 'Sending was successful';

    private const errorMessage = 'Аккаунт не individual';

    public string $status;

    public string $message;

    public static function transform($success = false): self
    {
        $dto = new self();
        $dto->status = $success ? self::successStatus : self::errorStatus;
        $dto->message = $success ? self::successMessage : self::errorMessage;

        return $dto;
    }
}
