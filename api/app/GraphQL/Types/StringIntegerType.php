<?php

declare(strict_types=1);

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\AST;


class StringIntegerType extends ScalarType
{
    public $description = /** @lang Markdown */
        'String or Integer type';

    public function parseValue($value)
    {
        //if (is_string($value) || is_int($value)) {
            return $value;
        //}
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        return AST::valueFromASTUntyped($valueNode);
    }

    public function serialize($value)
    {
        return $value;
    }


}
