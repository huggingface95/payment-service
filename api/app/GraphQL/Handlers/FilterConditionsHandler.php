<?php

namespace App\GraphQL\Handlers;

use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\WhereConditions\Operator;

class FilterConditionsHandler
{
    /**
     * @var \Nuwave\Lighthouse\WhereConditions\Operator
     */
    protected $operator;

    public function __construct(Operator $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  array<string, mixed>  $whereConditions
     */
    public function __invoke(
        object $builder,
        array $whereConditions,
        Model $model = null,
        string $boolean = 'and'
    ): void {
        if ($builder instanceof EloquentBuilder) {
            $model = $builder->getModel();
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
            if (preg_match('/^(.*?)FilterBy(.*)/', $hasCondition[1], $hasConditionArguments)) {
                $relation = $hasConditionArguments[1];
                $condition = [
                    'column' => strtolower(Str::snake($hasConditionArguments[2])),
                    'value' => $whereConditions['value'],
                    'operator' => $whereConditions['operator'],
                ];
            } elseif (preg_match('/^(.*?)Mixed(.*)/', $hasCondition[1], $hasConditionArguments)) {
                if (strpos('Or', $hasConditionArguments[2]) != -1) {
                    $c = 'OR';
                    $columns = explode('Or', $hasConditionArguments[2]);
                } else {
                    $c = 'AND';
                    $columns = explode('And', $hasConditionArguments[2]);
                }

                $relation = $hasConditionArguments[1];

                foreach ($columns as $column) {
                    $condition[$c][] = [
                        'column' => strtolower(Str::snake($column)),
                        'value' => $whereConditions['value'],
                        'operator' => $whereConditions['operator'],
                    ];
                }
            } else {
                $relation = $hasCondition[1];
            }

            $nestedBuilder = $this->handleHasCondition(
                $model,
                $relation,
                '=',
                $whereConditions['amount'] ?? 1,
                $condition
            );

            // @phpstan-ignore-next-line Simply wrong, maybe from Larastan?
            $builder->addNestedWhereQuery($nestedBuilder, $boolean);
        }

        if (! str_starts_with($whereConditions['column'] ?? 'null', 'has')) {
            if ($column = $whereConditions['column'] ?? null) {
                $this->assertValidColumnReference($column);

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
        ?array $condition = null
    ): QueryBuilder {
        return $model
            ->newQuery()
            ->whereHas(
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
            )
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
            $condition['column'] = $model->getTable().'.'.$condition['column'];
        } elseif (isset($condition[0]['column'])) {
            foreach ($condition as &$item) {
                $item['column'] = $model->getTable().'.'.$item['column'];
            }
        }

        return $condition;
    }
}
