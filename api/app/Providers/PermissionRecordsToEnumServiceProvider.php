<?php

namespace App\Providers;

use App\Models\Permissions;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Events\ManipulateAST;

class PermissionRecordsToEnumServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            ManipulateAST::class,
            function (ManipulateAST $manipulateAST): void {
                $permissions = Permissions::with('permissionList')->get()->groupBy(['permission_list_id', function ($permission) {
                    return $permission->permissionList->name;
                }])->collapse()->map(function ($permissions) {
                    return $permissions->pluck('display_name', 'id')->toArray();
                });

//                $manipulateAST->documentAST
//                    ->setTypeDefinition(
//                        $this->createUnionType($permissions->keys()->toArray())
//                    );

                foreach ($permissions as $listName => $records) {
                    $manipulateAST->documentAST
                        ->setTypeDefinition(
                            $this->createColumnsEnum($listName, $records)
                        );
                }
            }
        );
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

    private function createUnionType(array $list): UnionTypeDefinitionNode
    {
        $list = array_map(
            function (string $name): string {
                return strtoupper(Str::snake(preg_replace("/(:)/", '', $name)));
            },
            $list
        );

        $types = implode(" | ", $list);


        return Parser::unionTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"PermissionType"
union PermissionType = {$types}
GRAPHQL
        );
    }
}
