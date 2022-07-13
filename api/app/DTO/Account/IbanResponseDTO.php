<?php

namespace App\DTO\Account;

use App\Models\Account;

class IbanResponseDTO
{
    public int $id;

    public static function transform(Account $account): self
    {
        $dto = new self();
        $dto->id = $account->id;

        return $dto;
    }
}
