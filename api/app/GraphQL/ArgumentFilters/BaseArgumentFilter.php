<?php

namespace App\GraphQL\ArgumentFilters;

abstract class BaseArgumentFilter
{
    private static array $operators = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'like' => 'LIKE',
        'ilike' => 'ILIKE',
        'not_like' => 'NOT_LIKE',
        'in' => 'In',
        'not_in' => 'NotIn',
        'between' => 'Between',
        'not_between' => 'NotBetween',
        'is_null' => 'Null',
        'is_not_null' => 'NotNull',
    ];

    public function filterVariables(array $variables): array
    {
        $result = [];

        $this->searchVariablesRecursive($variables, $result);

        return $result;
    }

    public function filterQuery(array $selection): array
    {
        $variables = [];
        $result = [];

        foreach ($selection['selections'] ?? [] as $node) {
            foreach ($node['arguments'] ?? [] as $argument) {
                $this->parseArguments($argument, $variables);
            }
        }
        $this->searchVariablesRecursive($variables, $result);

        return $result;
    }

    private function parseArguments(array $argument, array &$conditions): void
    {
        if (isset($argument['value']['fields'])) {
            $c = [];
            foreach ($argument['value']['fields'] as $field) {
                $this->parseArguments($field, $c);
            }
            $conditions[$argument['name']['value']] = $c;
        } elseif (isset($argument['fields']) && ! isset($argument['name'])) {
            $c = [];
            foreach ($argument['fields'] as $field) {
                $this->parseArguments($field, $c);
            }
            $conditions[] = $c;
        } elseif (isset($argument['value']['values'])) {
            $c = [];
            foreach ($argument['value']['values'] as $value) {
                $this->parseArguments($value, $c);
            }
            $conditions[$argument['name']['value']] = $c;
        } elseif (isset($argument['value']['value'])) {
            $conditions[$argument['name']['value']] = $argument['value']['value'];
        }
    }

    private function searchVariablesRecursive(array $wheres, array &$result, string $default = 'AND'): void
    {
        foreach ($wheres as $k => $filter) {
            try {
                if (in_array(strtoupper($k), ['AND', 'OR'])) {
                    $default = strtoupper($k);
                    $this->searchVariablesRecursive($filter, $result, $default);
                } elseif (isset($filter['column']) && array_key_exists($filter['column'], static::$filters)) {
                    $filter['column'] = static::$filters[$filter['column']];
                    $filter['operator'] = self::$operators[strtolower($filter['operator'])] ?? $filter['operator'];
                    $result[$default][] = [$filter['column'], $filter['operator'] ?? '=', $filter['value']];
                } elseif (is_array($filter)) {
                    $this->searchVariablesRecursive($filter, $result, $default);
                }
            } catch (\Throwable) {
                continue;
            }
        }
    }
}
