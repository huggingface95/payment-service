<?php

namespace App\GraphQL\Queries;

use App\Models\Account;
use App\Models\AccountClient;
use App\Models\GroupRole;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AccountsQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id'=>$args['company_id'], 'group_role_id'=>$args['group_role_id'], 'group_type_id'=>$args['group_type_id']];

        return Account::paginate($args['paginate']['count']);
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function clientList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (isset($args['group_type'])) {
            if ($args['group_type'] == GroupRole::INDIVIDUAL) {
                return AccountClient::join('applicant_individual', 'account_clients.client_id', '=', 'applicant_individual.id')->get('account_clients.*');
            } else {
                return AccountClient::join('applicant_companies', 'account_clients.client_id', '=', 'applicant_companies.id')->get('account_clients.*');
            }
        }

        return AccountClient::all();
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function clientDetailsList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        if (count($args['query']) > 0) {
            $query = $args['query'];
            if (count($query['filter']) > 0) {
                $filter = $query['filter'];
                $account = Account::getAccountDetailsFilter($query, $filter);
            } else {
                $account = Account::orWhere('id', 'like', $query['account_name'])->orWhere('account_name', 'like', $query['account_name']);
            }
        }

        return $account->paginate(env('PAGINATE_DEFAULT_COUNT'));
    }
}
