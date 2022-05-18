<?php

namespace App\DTO\Account;

use App\Models\Accounts;

class IbanResponseDTO
{
    public int $id;

    public static function transform(Accounts $account): IbanResponseDTO
    {
        $dto = new self();
        $dto->id = $account->id;

        return $dto;
    }

}
