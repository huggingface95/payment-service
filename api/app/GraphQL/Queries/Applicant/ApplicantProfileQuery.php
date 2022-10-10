<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ApplicantProfileQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getProfile($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $applicant = auth()->user();

        return $applicant;
    }
}
