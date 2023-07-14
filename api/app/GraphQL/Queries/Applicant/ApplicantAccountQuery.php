<?php

namespace App\GraphQL\Queries\Applicant;

use App\Models\Account;
use App\Models\AccountState;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
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

        if (isset($args['orderBy']) && count($args['orderBy']) > 0) {
            $fields = $args['orderBy'];

            foreach ($fields as $field) {
                $accounts->orderBy(Str::lower($field['column']), $field['order']);
            }
        } else {
            $accounts->orderBy('id', 'DESC');
        }

        return $accounts->get();
    }
}
