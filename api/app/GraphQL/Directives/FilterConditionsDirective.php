<?php

namespace App\GraphQL\Directives;

use GraphQL\Error\Error;

class FilterConditionsDirective extends FilterConditionsBaseDirective
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Add a dynamically client-controlled WHERE condition to a fields query.
"""
directive @filterConditions(
    static: Input!

    """
    Reference a method that applies the client given conditions to the query builder.

    Expected signature: `(
        \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $builder,
        array<string, mixed> $whereConditions
    ): void`

    Consists of two parts: a class name and a method name, separated by an `@` symbol.
    If you pass only a class name, the method name defaults to `__invoke`.
    """
    handler: String = "App\\GraphQL\\Handlers\\FilterConditionsHandler"
) on ARGUMENT_DEFINITION
GRAPHQL;
    }


    /**
     * @throws Error
     */
    public function handleBuilder($builder, $value): object
    {
        $this->validation($value);

        if (null === $value) {
            return $builder;
        }

        $this->handle($builder, $value);

        return $builder;
    }

    protected function generatedInputSuffix(): string
    {
        return 'FilterConditions';
    }
}
