<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Enums\ClientTypeEnum;
use App\Models\Members;
use App\Models\Permissions;
use App\Models\PermissionsList;
use App\Models\Role;
use App\Services\PermissionsService;
use Illuminate\Support\Str;
use Tests\TestCase;

class UsersQueryTest extends TestCase
{
    public function testQueryUsersNoAuth(): void
    {
        $this->graphQL('
            {
                users {
                    data {
                        id
                        fullname
                        email
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testUserAuthData(): void
    {
        $member = Members::find(2);

        $this->postGraphQL([
            'query' => '
                {
                    userAuthData {
                        data {
                            id
                            fullname
                        }
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'userAuthData' => [
                    'data' => [
                        'id' => (string) $member->id,
                        'fullname' => $member->fullname,
                    ],
                ],
            ],
        ]);
    }

    /*private function getUserPermissions(Members $member): array
    {
        $clientType = ClientTypeEnum::APPLICANT->toString();

        $allPermissions = (new PermissionsService)->getPermissionsList(
            PermissionsList::get()->where('type', $clientType)
        );

        $permissionsList = '';
        foreach ($allPermissions as $permission) {
            $permissionsList .= $permission.PHP_EOL;
        }

        $permissions = $member->getAllPermissions()->groupBy(['permission_list_id', function ($permission) {
            return 'PERMISSION_'.strtoupper(Str::snake(str_replace(':', '', $permission->permissionList->name)));
        }])->collapse()->map(function ($permissions) {
            return $permissions->pluck('display_name', 'id')->toArray() ?? [];
        })->toArray() ?? [];

        foreach ($permissions as $listName => $records) {
            $permissions[$listName] = array_map(
                function ($id, string $name): string {
                    return strtoupper(Str::snake(preg_replace("/(\/)|(&)|(\()|(\))|(:)/", '', $name)));
                },
                array_keys($records),
                $records
            );
        }

        $basePermissions = [];
        foreach ($allPermissions as $permission) {
            $basePermissions[$permission] = [];
        }

        return [
            'userPermissions' => array_merge(
                $basePermissions,
                $permissions,
            ),
            'permissionsList' => $permissionsList,
        ];
    }*/
}
