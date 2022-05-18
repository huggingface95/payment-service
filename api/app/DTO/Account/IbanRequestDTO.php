<?php

namespace App\DTO\Account;

use App\Models\Accounts;

class IbanRequestDTO
{
    public int $id;

    public static function transform(Accounts $account): IbanRequestDTO
    {
        $dto = new self();
        $dto->id = $account->id;
        return $dto;
    }

}