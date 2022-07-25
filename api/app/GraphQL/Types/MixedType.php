<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\Type;

class MixedType extends Type
{
    /** @var string */
    public $name = 'MIXED';

    /** @var string */
    public $description = 'The `MIXED` type  takes on different values';

    /**
     * @param $value
     * @return mixed
     */
    public function serialize($value): mixed
    {
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function parseValue($value): mixed
    {
        return $value;
    }

    /**
     * @param  Node  $valueNode
     * @param  array|null  $variables
     * @return mixed
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        return $valueNode->value;
    }
}
