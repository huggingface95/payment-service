<?php

namespace App\GraphQL\Queries;

use App\Models\OperationType;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OperationTypeQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $operationTypes = OperationType::query();

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'transfer_type') {
            $transfer_type = $args['filter']['value'];
            $operationTypes->where('transfer_type', 'ilike', '%' . $transfer_type . '%')->get();
        }

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'fee_type_id') {
            $fee_type = $args['filter']['value'];
             $operationTypes->where('fee_type_id', '=', $fee_type)->get();
        }

        return $operationTypes->orderBy('id', 'ASC')->get();
    }
}
