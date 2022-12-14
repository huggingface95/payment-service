<?php

namespace App\DTO\GraphQLResponse;

class ActiveSessionMutatorResponse
{
    private const successStatus = 'Success';

    private const errorStatus = 'Error';

    private const successMessage = 'Update session was successful';

    private const errorMessage = 'Update error';

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
