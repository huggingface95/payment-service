<?php

namespace App\DTO\GraphQLResponse;

use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\PermissionsList;
use App\Services\PermissionsService;
use Illuminate\Support\Str;

class UserAuthResponse
{
    public Members|ApplicantIndividual $data;

    public array $permissions;

    public static function transform(Members|ApplicantIndividual $member): self
    {
        $dto = new self();
        $dto->data = $member;

        $userPermissions = $member->getAllPermissions()->groupBy(['permission_list_id', function ($permission) {
            return 'PERMISSION_'.strtoupper(Str::snake(str_replace(':', '', $permission->permissionList->name)));
        }])->collapse()->map(function ($permissions) {
            return $permissions->pluck('id')->toArray() ?? [];
        })->toArray() ?? [];

        $permissions = PermissionsList::get();
        $permissionsList = (new PermissionsService())->getPermissionsList($permissions);

        $basePermissions = [];
        foreach ($permissionsList as $permission) {
            $basePermissions[$permission] = [];
        }

        $dto->permissions = array_merge(
            $basePermissions,
            $userPermissions,
        );

        return $dto;
    }
}
