<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ApplicantIndividualQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function owners($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $ownerId = ApplicantCompany::pluck('owner_id')->toArray();

        return ApplicantIndividual::whereIn('id', $ownerId)->get();
    }
}
