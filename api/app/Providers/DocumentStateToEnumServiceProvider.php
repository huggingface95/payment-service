<?php

namespace App\Providers;

use App\Models\DocumentState;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Events\ManipulateAST;

class DocumentStateToEnumServiceProvider extends ServiceProvider
{

    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            ManipulateAST::class,
            function (ManipulateAST $manipulateAST): void {

                $records = DocumentState::query()->pluck('id', 'name')->toArray();

                $manipulateAST->documentAST
                    ->setTypeDefinition(
                        $this->createColumnsEnum($records)
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
                return
                    strtoupper(
                        Str::snake(preg_replace("/(\/)|(&)|(\()|(\))|(:)/", '', $name))
                    )
                    . ' @enum(value: "' . $id . '")';
            },
            array_keys($records),
            $records
        );

        $enumValuesString = implode("\n", $enumValues);

        $pEnumName = strtoupper(Str::snake(str_replace(':', '', 'DocumentStateEnum')));

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"DocumentStateEnum"
enum $pEnumName
{
    {$enumValuesString}
}
GRAPHQL
        );
    }
}
