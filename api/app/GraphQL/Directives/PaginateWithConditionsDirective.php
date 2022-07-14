<?php

namespace App\GraphQL\Directives;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Pagination\PaginateDirective;
use Nuwave\Lighthouse\Pagination\PaginationArgs;
use Nuwave\Lighthouse\Pagination\PaginationManipulator;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PaginateWithConditionsDirective extends PaginateDirective
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Query multiple model entries as a paginated list.
"""
directive @paginateWithConditions(
  """
  Which pagination style should be used.
  """
  type: PaginateType = PAGINATOR

  """
  Specify the class name of the model to use.
  This is only needed when the default model detection does not work.
  """
  model: String

  """
  Point to a function that provides a Query Builder instance.
  This replaces the use of a model.
  """
  builder: String

  """
  Apply scopes to the underlying query.
  """
  scopes: [String!]

  """
  Allow clients to query paginated lists without specifying the amount of items.
  Overrules the `pagination.default_count` setting from `lighthouse.php`.
  """
  defaultCount: Int

  """
  Limit the maximum amount of items that clients can request from paginated lists.
  Overrules the `pagination.max_count` setting from `lighthouse.php`.
  """
  maxCount: Int
) on FIELD_DEFINITION

"""
Options for the `type` argument of `@paginate`.
"""
enum PaginateType {
    """
    Offset-based pagination, similar to the Laravel default.
    """
    PAGINATOR

    """
    Offset-based pagination like the Laravel "Simple Pagination", which does not count the total number of records.
    """
    SIMPLE

    """
    Cursor-based pagination, compatible with the Relay specification.
    """
    CONNECTION
}
GRAPHQL;
    }

    public function manipulateFieldDefinition(DocumentAST &$documentAST, FieldDefinitionNode &$fieldDefinition, ObjectTypeDefinitionNode &$parentType): void
    {
        $paginationManipulator = new PaginationManipulator($documentAST);

        if ($this->directiveHasArgument('builder')) {
            // This is done only for validation
            $this->getResolverFromArgument('builder');
        } else {
            $paginationManipulator->setModelClass(
                $this->getModelClass()
            );
        }

        $paginationManipulator->transformToPaginatedField(
            $this->paginationType(),
            $fieldDefinition,
            $parentType,
            $this->defaultCount(),
            $this->paginateMaxCount()
        );
    }

    public function resolveField(FieldValue $fieldValue): FieldValue
    {
        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Paginator {
            $model = 'App\\Models\\'.Str::studly(strtolower(Str::singular($resolveInfo->fieldName)));

            $query = isset($args['query']) ? $model::getAccountFilter($args['query']) : $this->getModelClass()::query();

            return PaginationArgs::extractArgs($args, $this->optimalPaginationType($resolveInfo), $this->paginateMaxCount())
                ->applyToBuilder($query);
        });

        return $fieldValue;
    }
}
