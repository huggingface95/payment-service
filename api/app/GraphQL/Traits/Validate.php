<?php

namespace App\GraphQL\Traits;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Exceptions\DefinitionException;

/**
 * Directives may want to constrain database columns to an enum.
 *
 * @mixin \Nuwave\Lighthouse\Schema\Directives\BaseDirective
 */
trait Validate
{
    /**
     * @throws Error
     */
    public function validate(array $columns): void
    {
        foreach ($this->enums as $enumName) {
            /** @var EnumType $enumDefinition */
            $enumDefinition = $this->getEnumType($enumName);
            if ($enumDefinition) {
                if ($enumColumns = $this->formatForType($enumDefinition, $enumName)) {
                    $this->validateForType($columns, $enumColumns, $enumName);
                }
            }
        }
    }

    /**
     * @throws Error
     */
    private function validateForType(array $requestColumns, array $graphqlColumns, string $validationType): void
    {
        if ($validationType == self::REQUIRED_ENUM) {
            $this->validateRequired($requestColumns, $graphqlColumns);
        } elseif ($validationType == self::OPERATOR_ENUM) {
            $this->validateOperator($requestColumns, $graphqlColumns);
        } elseif ($validationType == self::TYPE_ENUM) {
            $this->validateType($requestColumns, $graphqlColumns);
        }
    }

    /**
     * @throws Error
     */
    private function validateRequired(array $requestColumns, array $graphqlColumns): void
    {
        foreach ($graphqlColumns as $graphqlColumn) {
            if (! array_key_exists($graphqlColumn, $requestColumns)) {
                throw new Error(
                    'COLUMN '.strtoupper(Str::snake($graphqlColumn))." REQUIRED PARAMETER IN {$this->definitionNode->type->name->value}",
                    $this->definitionNode
                );
            }
        }
    }

    /**
     * @throws Error
     */
    private function validateOperator(array $requestColumns, array $graphqlColumns): void
    {
        foreach ($requestColumns as $column => $columnData) {
            if (array_key_exists($column, $graphqlColumns) && $columnData['operator'] != $graphqlColumns[$column]) {
                throw new Error(
                    'COLUMN '.strtoupper(Str::snake($column)).' OPERATOR '.strtoupper(Str::snake($columnData['operator']))." TYPE WRONG OPERATOR IN {$this->definitionNode->type->name->value}",
                    $this->definitionNode
                );
            }
        }
    }

    /**
     * @throws Error
     */
    private function validateType(array $requestColumns, array $graphqlColumns): void
    {
        foreach ($requestColumns as $column => $columnData) {
            if (array_key_exists($column, $graphqlColumns)) {
                $this->checkValueAndType($graphqlColumns[$column], $columnData['value']);
            }
        }
    }

    /**
     * @throws Error
     */
    private function checkValueAndType(string $type, $value)
    {
        return $this->types[$type]->parseValue($value);
    }

    private function formatForType(EnumType $enum, string $type): ?array
    {
        if ($type == self::REQUIRED_ENUM) {
            return array_map(function ($col) {
                return $col->value;
            }, $enum->getValues());
        } elseif ($type == self::OPERATOR_ENUM || $type == self::TYPE_ENUM) {
            return collect($enum->getValues())->mapWithKeys(function ($col) {
                return [$col->name => $col->value];
            })->toArray();
        }

        return null;
    }

    private function getEnumType(string $name): ?Type
    {
        try {
            preg_match('/(.*?)FilterConditions/', $this->definitionNode->type->name->value, $matches);

            return $this->typeRegistry->get($matches[1].$name);
        } catch (DefinitionException) {
            return null;
        }
    }
}
