<?php

namespace App\GraphQL\Queries;

use App\Models\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ApplicantAccountQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $applicant = auth()->user();

        $accounts = Account::where('owner_id', $applicant->id);

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'is_show') {
            $accounts->where('is_show', $args['filter']['value'])->get();
        }

        return $accounts->get();
    }
}
