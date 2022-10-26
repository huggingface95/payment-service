<?php

namespace App\GraphQL\Queries;

use App\Models\Account;
use App\Models\AccountClient;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use GraphQL\Type\Definition\ResolveInfo;
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
                return AccountClient::where('client_type', ApplicantIndividual::class)->get();
            } else {
                return AccountClient::where('client_type', ApplicantCompany::class)->get();
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
        $account = Account::query();
        if (isset($args['query']) && count($args['query']) > 0) {
            $query = $args['query'];
            if (count($query['filter']) > 0) {
                $filter = $query['filter'];
                $account = Account::getAccountDetailsFilter($query, $filter);
            } elseif (isset($query['account_name'])) {
                $account = Account::orWhere('id', 'like', $query['account_name'])->orWhere('account_name', 'like', $query['account_name']);
            }
        }

        return $account->paginate(env('PAGINATE_DEFAULT_COUNT'));
    }

    public function accountRequisitesList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $account = Account::query();
        if (isset($args['query']) && count($args['query']) > 0) {
            $query = $args['query'];
            if (isset($query['id']) && !isset($query['filter'])) {
                $account = Account::find($query['id']);
            }
            if (isset($query['filter']) && count($query['filter']) > 0) {
                $filter = $query['filter'];
                $account = Account::getAccountRequisitesFilter($query, $filter)->first();
            } elseif (isset($query['account_name'])) {
                $account = Account::orWhere('id', 'like', $query['account_name'])->orWhere('account_name', 'like', $query['account_name']);
            }
        }

        return $account;
    }
}
