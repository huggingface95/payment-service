<?php

namespace App\GraphQL\Queries\Applicant;

use App\Models\Clickhouse\ActiveSession;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ApplicantDeviceQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $applicant = auth()->user();

        $query = DB::connection(env('APP_ENV') == 'testing' ? 'clickhouse_test' : 'clickhouse')
            ->table((new ActiveSession())->getTable())
            ->where('email', $applicant->email)
            ->orderBy('created_at', 'DESC')
            ->get();

        return $query;
    }
}
