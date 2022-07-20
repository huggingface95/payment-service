<?php

namespace App\GraphQL\Types;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class DecimalType extends ScalarType
{

    public $description = 'Decimal type';

    public function serialize($value)
    {
        if (is_numeric($value)){
            $cutting = explode('.', (string)$value);
            if (strlen($cutting[1]) == 5){
                return $value;
            }
        }

        throw new Error(
            'Decimal cannot represent dont float value: ' . $value
        );

    }

    public function parseValue($value)
    {
        return number_format((float)$value, 5, '.', '');
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        return $valueNode->value;
    }
}
