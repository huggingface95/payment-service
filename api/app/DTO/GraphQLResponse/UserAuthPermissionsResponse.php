<?php

namespace App\DTO\GraphQLResponse;

use Illuminate\Support\Collection;

class UserAuthPermissionsResponse
{
    public string $name;

    public Collection $list;

    public static function transform(string $name, Collection $list): self
    {
        $dto = new self();
        $dto->name = $name;
        $dto->list = $list;
        return $dto;
    }
}
