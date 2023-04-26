<?php

namespace App\GraphQL\Handlers;

use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
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
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $builder
     * @param array<string, mixed> $whereConditions
     *
     * @throws Error
     */
    public function __invoke(
        object $builder,
        array  $whereConditions,
        Model  $model = null,
        string $boolean = 'and'
    ): void
    {
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

        if (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1]['class'] != self::class) {
            array_walk_recursive($whereConditions, function ($value) use ($builder, $model) {
                if (str_contains($value, 'hasJoin')) {
                    preg_match('/hasJoin(.*?)Pivot/', $value, $match);
                    if (isset($match[1])) {
                        $relationship = $model->{$match[1]}();
                        /** @var Relation $relationship */
                        if ($relationship instanceof BelongsTo && !in_array($match[1], $this->joins)) {
                            $builder->join(
                                $relationship->getModel()->getTable(),
                                $relationship->getQualifiedOwnerKeyName(),
                                '=',
                                $relationship->getQualifiedForeignKeyName()
                            )->select('transfer_exchanges.*');
                        }
                        $this->joins[] = $match[1];
                    }
                }
            });
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
            $joinModel = null;

            if (preg_match('/^Join(.*?)Pivot(.*?)(Mixed|FilterBy|$)/', $hasCondition[1], $hasJoinConditionArguments)) {
                $joinModel = $model->{$hasJoinConditionArguments[1]}()->getModel();
                $joinRelationship = $joinModel->{$hasJoinConditionArguments[2]}();
                $hasCondition[1] = preg_replace('/(Join.*?Pivot)/', '', $hasCondition[1]);
            }

            if (preg_match('/^(.*?)FilterBy(.*)/', $hasCondition[1], $hasConditionArguments)) {
                $relation = $hasConditionArguments[1];
                $condition = $this->prefixConditionWithTableName([
                    'column' => strtolower(Str::snake($hasConditionArguments[2])),
                    'value' => $whereConditions['value'],
                    'operator' => $whereConditions['operator'],
                ], $joinModel ?? $model);
            } elseif (preg_match('/^(.*?)Mixed(.*)/', $hasCondition[1], $hasConditionArguments)) {
                $relation = $hasConditionArguments[1];
                $relationship = $joinRelationship ?? $model->$relation();
                if ($relationship instanceof MorphTo) {
                    $isMorph = true;
                    foreach (($joinModel ?? $model)->newModelQuery()->distinct()->pluck($relationship->getMorphType())->filter()->all() as $morph) {
                        $morph = Relation::getMorphedModel($morph) ?? $morph;
                        $condition[$morph] = $this->getMixedColumns($hasConditionArguments[2], $whereConditions, (new $morph())->getTable());
                    }
                } else {
                    $relationshipTableName = $relationship->getRelated()->getTable();
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
            $condition = $this->getMixedColumns($mixedColumns[1], $whereConditions, $model->getTable());
            $this->__invoke($builder, $condition, $model);
        }

        if (!preg_match('/^(has)|(Mixed)|(doesntHave)/', $whereConditions['column'] ?? 'null')) {
            if ($column = $whereConditions['column'] ?? null) {
                $this->assertValidColumnReference($column);
                $whereConditions = $this->prefixConditionWithTableName($whereConditions, $model);
                $this->operator->applyConditions($builder, $whereConditions, $boolean);
            }
        }
    }

    /**
     * @param array<string, mixed>|null $condition
     */
    public function handleHasCondition(
        Model  $model,
        string $relation,
        string $operator,
        int    $amount,
        ?array $condition = null,
        bool   $isMorph = false
    ): QueryBuilder
    {
        return $model
            ->newQuery()
            ->when($isMorph == false, function ($b) use ($relation, $condition, $operator, $amount) {
                return $b->whereHas(
                    $relation,
                    $condition
                        ? function ($builder) use ($condition): void {
                        $this->__invoke(
                            $builder,
                            $this->prefixConditionWithTableName(
                                $condition,
                                $builder->getModel()
                            ),
                            $builder->getModel()
                        );
                    }
                        : null,
                    $operator,
                    $amount
                );
            })
            ->when($isMorph, function (Builder $b) use ($relation, $condition, $operator, $amount) {
                return $b->whereHasMorph(
                    $relation,
                    is_array($condition) ? array_keys($condition) : Relation::morphMap(),
                    $condition
                        ? function ($builder) use ($condition): void {
                        $this->__invoke(
                            $builder,
                            $this->prefixConditionWithTableName(
                                $condition[get_class($builder->getModel())],
                                $builder->getModel()
                            ),
                            $builder->getModel()
                        );
                    }
                        : null,
                    $operator,
                    $amount
                );
            })
            ->getQuery();
    }

    public function handleDoesntHaveCondition(
        Model  $model,
        string $relation,
    ): QueryBuilder
    {
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
     * @param array<string, mixed> $condition
     * @return array<string, mixed>
     */
    protected function prefixConditionWithTableName(array $condition, Model $model): array
    {
        if (isset($condition['column'])) {
            $condition['column'] = $model->getTable() . '.' . $condition['column'];
        } elseif (isset($condition[0]['column'])) {
            foreach ($condition as &$item) {
                $item['column'] = $model->getTable() . '.' . $item['column'];
            }
        } elseif ((isset($condition['OR']) && is_array($condition['OR'])) || (isset($condition['AND']) && is_array($condition['AND']))) {
            foreach ($condition['OR'] ?? $condition['AND'] as &$item) {
                $item['column'] = $model->getTable() . '.' . $item['column'];
            }
        }

        return $condition;
    }

    private function getMixedColumns(string $column, array $whereConditions, string $table): ?array
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
            $column = strtolower(Str::snake($column));
            try {
                DB::table($table)->where($column, $whereConditions['operator'], $whereConditions['value'])->exists();
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
