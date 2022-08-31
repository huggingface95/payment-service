<?php

namespace App\GraphQL\Interfaces;

interface FilterConditionsInterface
{
    public const REQUIRED_ENUM = 'StaticRequired';

    public const OPERATOR_ENUM = 'StaticOperator';

    public const TYPE_ENUM = 'StaticType';

    public const ALLOWED_ENUM = 'Static';

    public const ALLOWED_INPUT = 'StaticInput';

    public const ENUMS = 'StaticEnum';
}
