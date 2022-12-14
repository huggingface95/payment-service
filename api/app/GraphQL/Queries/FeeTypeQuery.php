<?php

namespace App\GraphQL\Queries;

use App\Models\FeeType;
use App\Models\OperationType;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FeeTypeQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $feeTypes = FeeType::query();

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'operation_type_id') {
            $operationType = $args['filter']['value'];
            $operationTypes = OperationType::find($operationType);
            $feeTypes->where('id', '=', $operationTypes->fee_type_id);
        }

        return $feeTypes->orderBy('id', 'ASC')->get();
    }
}
