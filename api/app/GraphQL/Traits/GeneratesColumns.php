<?php

namespace App\GraphQL\Traits;

use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
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
        return (bool)$this->directiveArgValue('static');
    }

    protected function hasTree(): bool
    {
        return (bool)$this->directiveArgValue('tree');
    }

    protected function generateColumnsStatic(
        DocumentAST              &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode      &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ): string
    {
        $allowedColumnsStaticName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType) . 'Static';

        $requiredColumnsStaticName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType) . 'StaticRequired';

        $operatorColumnsStaticName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType) . 'StaticOperator';

        /** @var InputObjectTypeDefinitionNode $esim */
        $staticDefinitionNode = $documentAST->types[$allowedColumnsStaticName];

        $staticFields = collect($staticDefinitionNode->toArray(true)['fields']);

        $allowedColumns = $staticFields->pluck('name.value')->toArray();

        $requiredColumns = $staticFields->pluck('type.kind', 'name.value')->filter(function ($t) {
            return $t == NodeKind::NON_NULL_TYPE;
        })->keys()->toArray();

        $operators = $staticFields->pluck('directives', 'name.value')->map(function ($fieldDirective, $column) {
            return $fieldDirective[0]['name']['value'];
        })->toArray();

        $staticEnumAllowedColumnsDefinition = static::createAllowedColumnsEnum(
            $argDefinition,
            $parentField,
            $parentType,
            $allowedColumns,
            $allowedColumnsStaticName
        );

        $staticEnumRequiredColumnsDefinition = static::createRequiredColumnsEnum(
            $argDefinition,
            $parentField,
            $parentType,
            $requiredColumns,
            $requiredColumnsStaticName
        );

        $staticEnumOperationColumnsDefinition = static::createOperationColumnsEnum(
            $argDefinition,
            $parentField,
            $parentType,
            $operators,
            $operatorColumnsStaticName
        );

        if ($staticEnumAllowedColumnsDefinition) {
            $documentAST->setTypeDefinition($staticEnumAllowedColumnsDefinition);
        }

        if ($staticEnumRequiredColumnsDefinition) {
            $documentAST->setTypeDefinition($staticEnumRequiredColumnsDefinition);
        }

        if ($staticEnumOperationColumnsDefinition) {
            $documentAST->setTypeDefinition($staticEnumOperationColumnsDefinition);
        }

        return $allowedColumnsStaticName;
    }

    /**
     * Create the Enum that holds the allowed columns.
     *
     * @param array<mixed, string> $allowedColumns
     */
    protected function createAllowedColumnsEnum(
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode      &$parentField,
        ObjectTypeDefinitionNode &$parentType,
        array                    $allowedColumns,
        string                   $allowedColumnsEnumName
    ): ?EnumTypeDefinitionNode
    {
        $enumValues = array_map(
            function (string $columnName): string {
                return
                    strtoupper(
                        Str::snake($columnName)
                    )
                    . ' @enum(value: "' . $columnName . '")';
            },
            $allowedColumns
        );

        $enumValuesString = implode("\n", $enumValues);

        if (!strlen($enumValuesString)) {
            return null;
        }

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"Allowed column names for {$parentType->name->value}.{$parentField->name->value}.{$argDefinition->name->value}."
enum $allowedColumnsEnumName {
    {$enumValuesString}
}
GRAPHQL
        );
    }

    /**
     * Create the Enum that holds the allowed columns.
     *
     * @param array<mixed, string> $allowedColumns
     */
    protected function createRequiredColumnsEnum(
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode      &$parentField,
        ObjectTypeDefinitionNode &$parentType,
        array                    $requiredColumns,
        string                   $requiredColumnsEnumName
    ): ?EnumTypeDefinitionNode
    {
        $enumValues = array_map(
            function (string $columnName): string {
                return
                    strtoupper(
                        Str::snake($columnName)
                    )
                    . ' @enum(value: "' . $columnName . '")';
            },
            $requiredColumns
        );

        $enumValuesString = implode("\n", $enumValues);

        if (!strlen($enumValuesString)) {
            return null;
        }

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"Required column names for {$parentType->name->value}.{$parentField->name->value}.{$argDefinition->name->value}."
enum $requiredColumnsEnumName {
    {$enumValuesString}
}
GRAPHQL
        );
    }


    /**
     * Create the Enum that holds the allowed columns.
     *
     * @param array<mixed, string> $allowedColumns
     */
    protected function createOperationColumnsEnum(
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode      &$parentField,
        ObjectTypeDefinitionNode &$parentType,
        array                    $operationColumns,
        string                   $operationColumnsEnumName
    ): ?EnumTypeDefinitionNode
    {
        $enumValues = array_map(
            function (string $columnName, string $operator): string {
                return
                    $columnName
                    . ' @enum(value: "' . $operator . '")';
            },
            array_keys($operationColumns), $operationColumns
        );

        $enumValuesString = implode("\n", $enumValues);

        if (!strlen($enumValuesString)) {
            return null;
        }

        return Parser::enumTypeDefinition(/** @lang GraphQL */ <<<GRAPHQL
"Operator names for {$parentType->name->value}.{$parentField->name->value}.{$argDefinition->name->value}."
enum $operationColumnsEnumName {
    {$enumValuesString}
}
GRAPHQL
        );
    }
}
