<?php

namespace App\GraphQL\Builders;

use App\Models\Payments;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Sender
{
    public function filter($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        preg_match('/where.*?SENDER_NAME.*?value:(.*?)}/', $context->request()->get('query'), $match);

        return Payments::query()->select(['sender_name'])->when(count($match), function ($q) use ($match) {
            return $q->where('sender_name', 'LIKE', '%'.$match[1].'%');
        })->distinct('sender_name');
    }
}
