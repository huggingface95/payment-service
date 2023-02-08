<?php

namespace App\GraphQL\Queries;

use App\Models\PaymentStatus;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PaymentStatusQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $paymentStatus = PaymentStatus::query();

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'operation_type') {
            $transfer_type = $args['filter']['value'];
            $paymentStatus->where('operation_type', 'ilike', '%'.$transfer_type.'%')->get();
        }

        return $paymentStatus->orderBy('id', 'ASC')->get();
    }
}
