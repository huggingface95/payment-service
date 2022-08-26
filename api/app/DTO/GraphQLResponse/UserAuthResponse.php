<?php

namespace App\DTO\GraphQLResponse;

use App\Models\Members;
use Illuminate\Support\Str;


class UserAuthResponse
{
    public Members $data;

    public array $permissions;

    public static function transform(Members $member): self
    {
        $dto = new self();
        $dto->data = $member;
        $dto->permissions = $member->getAllPermissions()->groupBy(['permission_list_id', function ($permission) {
            return strtoupper(Str::snake(str_replace(':', '', $permission->permissionList->name)));
        }])->collapse()->map(function ($permissions){
            return $permissions->pluck('display_name');
        })->toArray();

        return $dto;
    }
}
