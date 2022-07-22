<?php

namespace App\GraphQL\Directives;

use App\GraphQL\Handlers\FilterConditionsHandler;
use App\GraphQL\Traits\GeneratesColumns;
use App\Providers\FilterConditionsServiceProvider;
use GraphQL\Error\Error;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Type\Definition\EnumType;
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

    public function __construct(TypeRegistry $typeRegistry)
    {
        $this->typeRegistry = $typeRegistry;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder the builder used to resolve the field
     * @param  array<string, mixed>  $value the client given value of the argument
     */
    protected function handle($builder, array $value): void
    {
        $handler = $this->directiveHasArgument('handler')
            ? $this->getResolverFromArgument('handler')
            : app(FilterConditionsHandler::class);

        $handler($builder, $value);
    }

    public function manipulateArgDefinition(
        DocumentAST &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ): void {
        if ($this->hasStatic()) {
            $restrictedWhereConditionsName = ASTHelper::qualifiedArgType($argDefinition, $parentField, $parentType).$this->generatedInputSuffix();
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
     * @throws DefinitionException
     */
    protected function validate(array $whereConditions)
    {
        try {
            $this->columns = [];
            $this->loadColumns($whereConditions);
            /** @var EnumType $enum */
            preg_match('/(.*?)FilterConditions/', $this->definitionNode->type->name->value, $matches);
            $enum = $this->typeRegistry->get($matches[1].'StaticRequired');
            $requiredColumns = array_map(function ($col) {
                return $col->value;
            }, $enum->getValues());
        } catch (DefinitionException) {
            throw new DefinitionException("Don't required enum type");
        }

        foreach ($requiredColumns as $requiredColumn) {
            if (! in_array($requiredColumn, $this->columns)) {
                throw new Error(
                    "COLUMN {$requiredColumn} REQUIRED PARAMETER IN {$this->definitionNode->type->name->value}",
                    $this->definitionNode
                );
            }
        }
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
            $this->columns[] = $whereCondition['column'];
        }
    }
}
