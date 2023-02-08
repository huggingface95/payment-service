<?php

namespace App\DTO\Email\Request;

use App\Models\Members;

class EmailMembersRequestDTO
{
    public string $emailTemplateName;

    public int $companyId;

    public Members $members;

    public object $data;

    public string $email;

    public static function transform(Members $members, array $data, string $emailTemplateName): self
    {
        $dto = new self();
        $dto->companyId = $members->company_id;
        $dto->emailTemplateName = $emailTemplateName;
        $dto->members = $members;
        $dto->email = $data['email'];
        $dto->data = (object) $data;

        return $dto;
    }
}
