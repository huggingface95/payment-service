<?php

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Illuminate\Support\Carbon;

/**
 * Read more about scalars here https://webonyx.github.io/graphql-php/type-definitions/scalars
 */
final class DateTimeUtc extends ScalarType
{
    /**
     * Serializes an internal value to include in a response.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function serialize($value)
    {
        return Carbon::parse($value)->format("Y-m-d\TH:i:s.vp");
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function parseValue($value)
    {
        if (Carbon::createFromFormat('Y-m-d\TH:i:s.vp', $value) === false) {
            throw new Error('Cannot represent following value as DateTimeUtc: '.Utils::printSafeJson($value));
        }

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * @param  \GraphQL\Language\AST\Node  $valueNode
     * @param  array<string, mixed>|null  $variables
     * @return mixed
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if (Carbon::createFromFormat('Y-m-d\TH:i:s.vp', $valueNode->value) === false) {
            throw new Error('Cannot represent following value as DateTimeUtc: '.Utils::printSafeJson($valueNode->value));
        }

        return $valueNode->value;
    }
}
