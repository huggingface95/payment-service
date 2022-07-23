<?php

namespace App\GraphQL\Traits;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Schema\AST\ASTHelper;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;

/**
 * Directives may want to constrain database columns to an enum.
 *
 * @mixin \Nuwave\Lighthouse\Schema\Directives\BaseDirective
 */
trait GeneratesColumns
{
    protected function hasStatic(): bool
    {
        return (bool) $this->directiveArgValue('static');
    }

    private function getDefinitionNode(DocumentAST $documentAST, $name): Collection
    {
        $staticDefinitionNode = $documentAST->types[$name];

        return collect($staticDefinitionNode->toArray(true)['fields']);
    }

    private function getColumnsForAllowedEnum(Collection $fields): array
    {
        return $fields->pluck('name.value')->toArray();
    }

    private function getColumnsForRequiredEnum(Collection $fields): array
    {
        return $fields->pluck('type.kind', 'name.value')->filter(function ($t) {
            return $t == NodeKind::NON_NULL_TYPE;
        })->keys()->toArray();
    }

    private function getColumnsForOperatorEnum(Collection $fields): array
    {
        return $fields->pluck('directives', 'name.value')->map(function ($fieldDirective, $column) {
            return $fieldDirective[0]['name']['value'];
        })->toArray();
    }

    private function getColumnsForTypeEnum(Collection $fields): array
    {
        return $fields->mapWithKeys(function ($field) {
            return array_key_exists('name', $field['type'])
                ? [$field['name']['value'] => $field['type']['name']['value']]
                : [$field['name']['value'] => $field['type']['type']['name']['value']];
        })->toArray();
    }

    protected function generateColumnsStatic(
        DocumentAST &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ): string {
        $types = collect();

        $fullName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType);

        $fields = $this->getDefinitionNode($documentAST, $fullName.self::ALLOWED_ENUM);

        foreach ([
            self::ALLOWED_ENUM,
            self::REQUIRED_ENUM,
            self::OPERATOR_ENUM,
            self::TYPE_ENUM,
        ] as $type) {
            if ($type == self::ALLOWED_ENUM) {
                $types->put($type, $this->getColumnsForAllowedEnum($fields));
            } elseif ($type == self::REQUIRED_ENUM) {
                $types->put($type, $this->getColumnsForRequiredEnum($fields));
            } elseif ($type == self::OPERATOR_ENUM) {
                $types->put($type, $this->getColumnsForOperatorEnum($fields));
            } elseif ($type == self::TYPE_ENUM) {
                $types->put($type, $this->getColumnsForTypeEnum($fields));
            }
        }

        $types = $types->filter(function ($type) {
            return count($type);
        });

        foreach ($types as $type => $data) {
            $enumColumnsDefinition = static::createColumnsEnum($argDefinition, $parentField, $parentType, $data, $type, $fullName.$type);
            $documentAST->setTypeDefinition($enumColumnsDefinition);
        }

        return $fullName.self::ALLOWED_ENUM;
    }

    protected function createColumnsEnum(
        InputValueDefinitionNode $argDefinition,
        FieldDefinitionNode $parentField,
        ObjectTypeDefinitionNode $parentType,
        array $columns,
        string $type,
        string $ColumnsEnumName
    ): ?EnumTypeDefinitionNode {
        if ($type == self::REQUIRED_ENUM) {
            $enumValues = array_map(
                function (string $columnName): string {
                    return
                        $columnName
                        .' @enum(value: "'.$columnName.'")';
                },
                $columns
            );
        } elseif ($type == self::OPERATOR_ENUM || $type == self::TYPE_ENUM) {
            $enumValues = array_map(
                function (string $columnName, string $v): string {
                    return
                        $columnName
                        .' @enum(value: "'.$v.'")';
                },
                array_keys($columns), $columns
            );
        } else {
            $enumValues = array_map(
                function (string $columnName): string {
                    return
                        strtoupper(
                            Str::snake($columnName)
                        )
                        .' @enum(value: "'.$columnName.'")';
                },
                $columns
            );
        }

        $enumValuesString = implode("\n", $enumValues);

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"Column names for {$parentType->name->value}.{$parentField->name->value}.{$argDefinition->name->value}."
enum $ColumnsEnumName {
    {$enumValuesString}
}
GRAPHQL
        );
    }
}
