<?php

namespace App\GraphQL\Handlers;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\WhereConditions\Operator;

class FilterConditionsHandler
{
    /**
     * @var \Nuwave\Lighthouse\WhereConditions\Operator
     */
    protected $operator;

    private array $joins = [];

    public function __construct(Operator $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  array<string, mixed>  $whereConditions
     *
     * @throws Error
     */
    public function __invoke(
        object $builder,
        array $whereConditions,
        Model $model = null,
        string $boolean = 'and'
    ): void {
        if ($builder instanceof EloquentBuilder) {
            $model = $builder->getModel();
        } else {
            if ($model) {
                $builder = new EloquentBuilder($builder);
                $builder->setModel($model);
                foreach ($model->getGlobalScopes() as $identifier => $scope) {
                    $builder->withGlobalScope($identifier, $scope);
                }
            }
        }

        if ($andConnectedConditions = $whereConditions['AND'] ?? null) {
            $builder->whereNested(
                function ($builder) use ($andConnectedConditions, $model): void {
                    foreach ($andConnectedConditions as $condition) {
                        $this->__invoke($builder, $condition, $model);
                    }
                },
                $boolean
            );
        }

        if ($orConnectedConditions = $whereConditions['OR'] ?? null) {
            $builder->whereNested(
                function ($builder) use ($orConnectedConditions, $model): void {
                    foreach ($orConnectedConditions as $condition) {
                        $this->__invoke($builder, $condition, $model, 'or');
                    }
                },
                $boolean
            );
        }

        if (isset($whereConditions['column']) && preg_match('/^has(.*)/', $whereConditions['column'], $hasCondition)) {
            $condition = null;
            $isMorph = false;
            $joinRelationship = null;
            $pivotModel = null;

            if (preg_match('/^(.*?)Pivot(.*?)(Mixed|FilterBy|$)/', $hasCondition[1], $hasJoinConditionArguments)) {
                $pivotModel = $model->{$hasJoinConditionArguments[1]}()->getModel();
                $joinRelationship = $pivotModel->{$hasJoinConditionArguments[2]}();
                $hasCondition[1] = preg_replace('/(.*?)(Pivot)(.*)/', '$1.$3', $hasCondition[1]);
            }

            if (preg_match('/^(.*?)FilterBy(.*)/', $hasCondition[1], $hasConditionArguments)) {
                $relation = $hasConditionArguments[1];
                $condition = [
                    'column' => strtolower(Str::snake($hasConditionArguments[2])),
                    'value' => $whereConditions['value'],
                    'operator' => $whereConditions['operator'],
                ];
            } elseif (preg_match('/^(.*?)Mixed(.*)/', $hasCondition[1], $hasConditionArguments)) {
                $relation = $hasConditionArguments[1];
                $relationship = $joinRelationship ?? $model->$relation();
                if ($relationship instanceof MorphTo) {
                    $isMorph = true;
                    foreach (($pivotModel ?? $model)->newModelQuery()->distinct()->pluck($relationship->getMorphType())->filter()->all() as $morph) {
                        $morph = Relation::getMorphedModel($morph) ?? $morph;
                        $condition[$morph] = $this->getMixedColumns($hasConditionArguments[2], $whereConditions, (new $morph()));
                    }
                } else {
                    $relationshipTableName = $relationship->getRelated();
                    $condition = $this->getMixedColumns($hasConditionArguments[2], $whereConditions, $relationshipTableName);
                }
            } else {
                $relation = $hasCondition[1];
            }

            $nestedBuilder = $this->handleHasCondition(
                $joinModel ?? $model,
                $relation,
                '>=',
                $whereConditions['amount'] ?? 1,
                $condition,
                $isMorph
            );
            $builder->addNestedWhereQuery($nestedBuilder, $boolean);
        }

        if (isset($whereConditions['column']) && preg_match('/^doesntHave(.*)/', $whereConditions['column'], $hasNotCondition)) {
            $relation = $hasNotCondition[1];
            $nestedBuilder = $this->handleDoesntHaveCondition($model, $relation);
            $builder->addNestedWhereQuery($nestedBuilder, $boolean);
        }

        if (isset($whereConditions['column']) && preg_match('/^Mixed(.*)/', $whereConditions['column'], $mixedColumns)) {
            $condition = $this->getMixedColumns($mixedColumns[1], $whereConditions, $model);
            $this->__invoke($builder, $condition, $model);
        }

        if (! preg_match('/^(has)|(Mixed)|(doesntHave)/', $whereConditions['column'] ?? 'null')) {
            if ($column = $whereConditions['column'] ?? null) {
                $this->assertValidColumnReference($column);
                $whereConditions = $this->prefixConditionWithTableName($whereConditions, $model);
                $this->operator->applyConditions($builder, $whereConditions, $boolean);
            }
        }
    }

    /**
     * @param  array<string, mixed>|null  $condition
     */
    public function handleHasCondition(
        Model $model,
        string $relation,
        string $operator,
        int $amount,
        ?array $condition = null,
        bool $isMorph = false
    ): QueryBuilder {
        return $model
            ->newQuery()
            ->whereHas(
                $relation,
                $condition
                    ? function ($builder) use ($condition, $isMorph): void {
                        $this->__invoke(
                        $builder,
                        $this->prefixConditionWithTableName(
                            $isMorph ? $condition[get_class($builder->getModel())] : $condition,
                            $builder->getModel()
                        ),
                        $builder->getModel()
                    );
                    }
                    : null,
                $operator,
                $amount
            )
            ->getQuery();
    }

    public function handleDoesntHaveCondition(
        Model $model,
        string $relation,
    ): QueryBuilder {
        return $model
            ->newQuery()
            ->whereDoesntHave($relation)
            ->getQuery();
    }

    /**
     * Ensure the column name is well formed to prevent SQL injection.
     *
     * @throws \GraphQL\Error\Error
     */
    protected function assertValidColumnReference(string $column): void
    {
        // A valid column reference:
        // - must not start with a digit, dot or hyphen
        // - must contain only alphanumerics, digits, underscores, dots, hyphens or JSON references
        $match = \Safe\preg_match('/^(?![0-9.-])([A-Za-z0-9_.-]|->)*$/', $column);
        if (0 === $match) {
            throw new Error(
                self::invalidColumnName($column)
            );
        }
    }

    public static function invalidColumnName(string $column): string
    {
        return "Column names may contain only alphanumerics or underscores, and may not begin with a digit, got: $column";
    }

    /**
     * If the condition references a column, prefix it with the table name.
     *
     * This is important for queries which can otherwise be ambiguous, for
     * example when multiple tables with a column "id" are involved.
     *
     * @param  array<string, mixed>  $condition
     * @return array<string, mixed>
     */
    protected function prefixConditionWithTableName(array $condition, Model $model): array
    {
        if (isset($condition['column'])) {
            if (! str_contains($condition['column'], '.')) {
                $condition['column'] = $model->getTable().'.'.$condition['column'];
            }
        } elseif (isset($condition[0]['column'])) {
            foreach ($condition as &$item) {
                if (! str_contains($item['column'], '.')) {
                    $item['column'] = $model->getTable().'.'.$item['column'];
                }
            }
        } elseif ((isset($condition['OR']) && is_array($condition['OR'])) || (isset($condition['AND']) && is_array($condition['AND']))) {
            foreach ($condition['OR'] ?? $condition['AND'] as &$item) {
                if (! str_contains($item['column'], '.')) {
                    $item['column'] = $model->getTable().'.'.$item['column'];
                }
            }
        }

        return $condition;
    }

    private function getMixedColumns(string $column, array $whereConditions, Model $model): ?array
    {
        $condition = null;

        if (strpos('Or', $column) != -1) {
            $c = 'OR';
            $columns = explode('Or', $column);
        } else {
            $c = 'AND';
            $columns = explode('And', $column);
        }

        foreach ($columns as $column) {
            if ($column == 'Id' && is_string($whereConditions['value'])){
                if ($model instanceof ApplicantIndividual && !str_contains($whereConditions['value'], ApplicantIndividual::ID_PREFIX)){
                    continue;
                } elseif ($model instanceof ApplicantCompany && !str_contains($whereConditions['value'], ApplicantCompany::ID_PREFIX)){
                    continue;
                }
            }
            $column = strtolower(Str::snake($column));
            try {
                $model->newQuery()->where($column, $whereConditions['operator'], $whereConditions['value'])->exists();
            } catch (QueryException) {
                continue;
            }
            $condition[$c][] = [
                'column' => $column,
                'value' => $whereConditions['value'],
                'operator' => $whereConditions['operator'],
            ];
        }

        return $condition;
    }
}
