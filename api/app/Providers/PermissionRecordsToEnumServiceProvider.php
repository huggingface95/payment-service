<?php

namespace App\Providers;

use App\Enums\ClientTypeEnum;
use App\Models\Permissions;
use App\Models\PermissionsList;
use App\Services\PermissionsService;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Events\ManipulateAST;

class PermissionRecordsToEnumServiceProvider extends ServiceProvider
{
    protected PermissionsService $permissionsService;

    public function __construct()
    {
        $this->permissionsService = new PermissionsService();
    }

    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            ManipulateAST::class,
            function (ManipulateAST $manipulateAST): void {
                $clientType = Auth::guard('api')->check() ? ClientTypeEnum::MEMBER->toString() : ClientTypeEnum::APPLICANT->toString();
                $permissions = $this->getPermissions($clientType);
                if ($permissions->count()) {
                    $manipulateAST->documentAST
                        ->setTypeDefinition(
                            $this->createObjectType($permissions->keys()->toArray())
                        );
                }

                foreach ($permissions as $listName => $records) {
                    $manipulateAST->documentAST
                        ->setTypeDefinition(
                            $this->createColumnsEnum($listName, $records)
                        );
                }

                // PermissionAuth
                $list = $this->permissionsService->getPermissionsList(PermissionsList::get()->where('type', $clientType));
                if (count($list)) {
                    $manipulateAST->documentAST->setTypeDefinition(
                        $this->createObjectType($list, 'PermissionAuth')
                    );
                }
            }
        );
    }

    private function getPermissions(string $clientType): Collection
    {
        return Permissions::with('permissionList')
            ->whereHas('permissionList', function ($query) use ($clientType) {
                $query->where('type', $clientType);
            })
            ->get()
            ->groupBy(['permission_list_id', function ($permission) {
                return $permission->permissionList->name;
            }])
            ->collapse()
            ->map(function ($permissions) {
                return $permissions->pluck('display_name', 'id')->toArray();
            });
    }


    private function createColumnsEnum(
        string $enumName,
        array  $permissions
    ): ?EnumTypeDefinitionNode
    {
        $enumValues = array_map(
            function (int $id, string $name): string {
                return
                    strtoupper(
                        Str::snake(preg_replace("/(\/)|(&)|(\()|(\))|(:)/", '', $name))
                    )
                    . ' @enum(value: "' . $id . '")';
            },
            array_keys($permissions),
            $permissions
        );

        $enumValuesString = implode("\n", $enumValues);

        $pEnumName = 'PERMISSION_' . strtoupper(Str::snake(str_replace(':', '', $enumName)));

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"Permission list name {$enumName}"
enum $pEnumName
{
    {$enumValuesString}
}
GRAPHQL
        );
    }

    private function createObjectType(array $list, $typeName = 'PermissionType'): ObjectTypeDefinitionNode
    {
        if ($typeName === 'PermissionAuth') {
            $list = array_map(
                function (string $name): string {
                    return $name . ': [' . $name . '!]!';
                },
                $list
            );
        } else {
            $list = array_map(
                function (string $name): string {
                    $name = 'PERMISSION_' . strtoupper(Str::snake(preg_replace('/(:)/', '', $name)));

                    return $name . ': ' . $name;
                },
                $list
            );
        }

        $types = implode("\n", $list);

        return Parser::objectTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"$typeName"
type $typeName {
{$types}
}
GRAPHQL
        );
    }
}
