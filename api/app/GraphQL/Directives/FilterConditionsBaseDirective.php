<?php

namespace App\GraphQL\Directives;

use App\GraphQL\Handlers\FilterConditionsHandler;
use App\GraphQL\Traits\GeneratesColumns;
use App\GraphQL\Types\MixedType;
use App\Providers\FilterConditionsServiceProvider;
use GraphQL\Error\Error;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\AST\ASTHelper;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgManipulator;

abstract class FilterConditionsBaseDirective extends BaseDirective implements ArgBuilderDirective, ArgManipulator
{
    use GeneratesColumns;

    private TypeRegistry $typeRegistry;

    private array $columns = [];

    private array $operators = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'like' => 'LIKE',
        'not_like' => 'NOT_LIKE',
        'in' => 'In',
        'not_in' => 'NotIn',
        'between' => 'Between',
        'not_between' => 'NotBetween',
        'is_null' => 'Null',
        'is_not_null' => 'NotNull',
    ];

    private array $types;

    public function __construct(TypeRegistry $typeRegistry)
    {
        $this->typeRegistry = $typeRegistry;
        $this->types = Type::getStandardTypes();
        $this->types['Mixed'] = new MixedType();
    }

    /**
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $builder the builder used to resolve the field
     * @param array<string, mixed> $value the client given value of the argument
     */
    protected function handle($builder, array $value): void
    {
        $handler = $this->directiveHasArgument('handler')
            ? $this->getResolverFromArgument('handler')
            : app(FilterConditionsHandler::class);

        $handler($builder, $value);
    }

    public function manipulateArgDefinition(
        DocumentAST              &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode      &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ): void
    {
        if ($this->hasStatic()) {
            $restrictedWhereConditionsName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType) . $this->generatedInputSuffix();
            $argDefinition->type = Parser::namedType($restrictedWhereConditionsName);
            $allowedColumnsEnumName = $this->generateColumnsStatic($documentAST, $argDefinition, $parentField, $parentType);
            $documentAST
                ->setTypeDefinition(
                    FilterConditionsServiceProvider::createWhereConditionsInputType(
                        $restrictedWhereConditionsName,
                        "Dynamic WHERE conditions for the `{$argDefinition->name->value}` argument on the query `{$parentField->name->value}`.",
                        $allowedColumnsEnumName
                    )
                );
        } else {
            $argDefinition->type = Parser::namedType(FilterConditionsServiceProvider::DEFAULT_WHERE_CONDITIONS);
        }
    }

    /**
     * Get the suffix that will be added to generated input types.
     */
    abstract protected function generatedInputSuffix(): string;

    /**
     * @throws Error
     */
    protected function validateRequired(array $whereConditions): bool
    {
        try {
            $this->columns = [];
            $this->loadColumns($whereConditions);
            /** @var EnumType $enum */
            preg_match('/(.*?)FilterConditions/', $this->definitionNode->type->name->value, $matches);
            $enum = $this->typeRegistry->get($matches[1] . 'StaticRequired');
            $requiredColumns = array_map(function ($col) {
                return $col->value;
            }, $enum->getValues());
        } catch (DefinitionException) {
            return true;
        }

        foreach ($requiredColumns as $requiredColumn) {
            if (!array_key_exists($requiredColumn, $this->columns)) {
                throw new Error(
                    "COLUMN " . strtoupper(Str::snake($requiredColumn)) . " REQUIRED PARAMETER IN {$this->definitionNode->type->name->value}",
                    $this->definitionNode
                );
            }
        }
        return true;
    }

    /**
     * @throws Error
     */
    protected function validateOperator(array $whereConditions): bool
    {
        try {
            $this->columns = [];
            $this->loadColumns($whereConditions);
            /** @var EnumType $enum */
            preg_match('/(.*?)FilterConditions/', $this->definitionNode->type->name->value, $matches);
            $enum = $this->typeRegistry->get($matches[1] . 'StaticOperator');

            $operationColumns = collect($enum->getValues())->mapWithKeys(function ($col) {
                return [$col->name => $col->value];
            })->toArray();

        } catch (DefinitionException) {
            return true;
        }

        foreach ($this->columns as $column => $data) {
            if (array_key_exists($column, $operationColumns) && $data['operator'] != $operationColumns[$column]) {
                throw new Error(
                    "COLUMN " . strtoupper(Str::snake($column)) . " OPERATOR " . strtoupper(Str::snake($data['operator'])) . " TYPE WRONG OPERATOR IN {$this->definitionNode->type->name->value}",
                    $this->definitionNode
                );
            }
        }
        return true;
    }

    /**
     * @throws Error
     */
    protected function validateType(array $whereConditions): bool
    {
        try {
            $this->columns = [];
            $this->loadColumns($whereConditions);
            /** @var EnumType $enum */
            preg_match('/(.*?)FilterConditions/', $this->definitionNode->type->name->value, $matches);
            $enum = $this->typeRegistry->get($matches[1] . 'StaticType');

            $typeColumns = collect($enum->getValues())->mapWithKeys(function ($col) {
                return [$col->name => $col->value];
            })->toArray();

        } catch (DefinitionException) {
            return true;
        }


        foreach ($this->columns as $column => $data) {
            if (array_key_exists($column, $typeColumns)) {
                $this->checkValueAndType($typeColumns[$column], $data['value']);
            }
        }
        return true;
    }


    /**
     * @throws Error
     */
    private function checkValueAndType(string $type, $value)
    {
        return $this->types[$type]->parseValue($value);
    }

    private function loadColumns(array $whereCondition)
    {
        if ($andConnectedConditions = $whereCondition['AND'] ?? null) {
            foreach ($andConnectedConditions as $condition) {
                $this->loadColumns($condition);
            }
        }

        if ($andConnectedConditions = $whereCondition['OR'] ?? null) {
            foreach ($andConnectedConditions as $condition) {
                $this->loadColumns($condition);
            }
        }

        if (array_key_exists('column', $whereCondition)) {
            $operator = array_search($whereCondition['operator'], $this->operators);

            $this->columns[$whereCondition['column']] = [
                'operator' => $operator,
                'value' => $whereCondition['value']
            ];
        }
    }
}
