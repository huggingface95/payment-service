<?php

namespace App\DTO\Email\Request;

use App\Models\Account;

class EmailMemberRequestDTO
{
    public string $emailTemplateName;
    public Account $account;
    public object $data;
    public string $email;

    public static function transform(Account $account, array $data, string $emailTemplateName): self
    {
        $dto = new self();

        $dto->emailTemplateName = $emailTemplateName;
        $dto->account = $account;
        $dto->email = $data['email'];
        $dto->data = (object) $data;

        return $dto;
    }
}
