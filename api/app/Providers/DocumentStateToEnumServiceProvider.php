<?php

namespace App\Providers;

use App\Models\DocumentState;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Events\ManipulateAST;

class DocumentStateToEnumServiceProvider extends ServiceProvider
{

    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            ManipulateAST::class,
            function (ManipulateAST $manipulateAST): void {
                $values = DocumentState::query()->orderBy('id')->get()->mapWithKeys(function ($r) {
                    return [$r->name => $r->id];
                })->toArray();

                $manipulateAST->documentAST
                    ->setTypeDefinition(
                        $this->createColumnsEnum($values)
                    );
            }
        );
    }


    private function createColumnsEnum(
        array $records
    ): ?EnumTypeDefinitionNode
    {
        $enumValues = array_map(
            function (string $name, int $id): string {
                return $name . ' @enum(value: "' . $id . '")';
            },
            array_keys($records),
            $records
        );

        $enumValuesString = implode("\n", $enumValues);

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"DocumentStateEnum"
enum DocumentStateEnum
{
    {$enumValuesString}
}
GRAPHQL
        );
    }
}
