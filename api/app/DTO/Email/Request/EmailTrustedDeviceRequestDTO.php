<?php

namespace App\DTO\Email\Request;

use App\Models\ApplicantIndividual;

class EmailTrustedDeviceRequestDTO
{
    public int $companyId;

    public string $fullName;

    public string $email;

    public string $createdAt;

    public string $ip;

    public string $os;

    public string $type;

    public string $model;

    public string $browser;

    public static function transform(array $activeSession, ApplicantIndividual $user): self
    {
        $dto = new self();
        $dto->fullName = $user->fullname;
        $dto->companyId = $user->company_id;
        $dto->email = $user->email;
        $dto->createdAt = date('Y-m-d H:i:s');
        $dto->ip = $activeSession['ip'];
        $dto->os = $activeSession['platform'];
        $dto->type = $activeSession['device_type'];
        $dto->model = $activeSession['model'];
        $dto->browser = $activeSession['browser'];

        return $dto;
    }
}
