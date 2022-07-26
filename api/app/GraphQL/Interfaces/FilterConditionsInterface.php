<?php

namespace App\GraphQL\Interfaces;

interface FilterConditionsInterface
{
    const REQUIRED_ENUM = 'StaticRequired';

    const OPERATOR_ENUM = 'StaticOperator';

    const TYPE_ENUM = 'StaticType';

    const ALLOWED_ENUM = 'Static';
}
