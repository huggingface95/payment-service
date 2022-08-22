<?php

namespace App\DTO\GraphQLResponse;

use App\DTO\TransformerDTO;
use App\Models\Members;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class UserAuthResponse
{
    public Members $data;

    public Collection $permissions;

    public static function transform(Members $member): self
    {
        $dto = new self();
        $dto->data = $member;
        $dto->permissions = $member->getAllPermissions()->groupBy(['permission_list_id', function ($permission) {
            return $permission->permissionList->name;
        }])->collapse()->map(function ($permissions, $list) {
            return TransformerDTO::transform(UserAuthPermissionsResponse::class, strtoupper(Str::snake(str_replace(':', '', $list))), $permissions);
        });
        return $dto;
    }
}
