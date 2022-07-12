<?php

namespace App\GraphQL\Queries;

use App\Models\AccountClient;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use App\Models\GroupType;
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
                return AccountClient::join('applicant_individual','account_clients.client_id','=','applicant_individual.id')->get('account_clients.*');
            } else {
                return AccountClient::join('applicant_companies','account_clients.client_id','=','applicant_companies.id')->get('account_clients.*');
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

        if (isset($args['query'])) {
            $query = $args['query'];
            if (isset($query['filter'])) {
                $filter = $query['filter'];
                return Account::join('companies','accounts.company_id','=','companies.id')
                    ->join('group_role','accounts.group_role_id','=','group_role.id')
                    ->join('applicant_individual','accounts.owner_id','=','applicant_individual.id')
                    ->join('payment_provider','accounts.payment_provider_id','=','payment_provider.id')
                    ->select('accounts.*')
                    ->where(function($q) use ($query) {
                        $q->orWhere('accounts.id', 'like', $query['account_name'])
                            ->orWhere('accounts.account_name', 'like', $query['account_name']);
                    })
                    ->where(function($q) use ($filter) {
                        $q->orWhere('companies.id','like', $filter['company'] ?? '')
                            ->orWhere('companies.name', 'like', $filter['company'] ?? '');
                    })
                    ->where('accounts.group_type_id','=',$filter['group_type_id'] ?? null)
                    ->where(function($q) use ($filter) {
                        $q->orWhere('group_role.id','like', $filter['group_role'] ?? '%')
                            ->orWhere('group_role.name', 'like', $filter['group_role'] ?? '%');
                    })
                    ->where(function($q) use ($filter) {
                        $q->orWhere('payment_provider.id','like', $filter['payment_provider'] ?? '%')
                            ->orWhere('payment_provider.name', 'like', $filter['payment_provider'] ?? '%');
                    })
                    ->where(function($q) use ($filter) {
                        $q->orWhere('applicant_individual.id','like', $filter['owner'] ?? '%')
                            ->orWhere('applicant_individual.fullname', 'like', $filter['owner'] ?? '%');
                    })
                    ->paginate(env('PAGINATE_DEFAULT_COUNT'));
            } else {
                return Account::orWhere('id','like',$query['account_name'])->orWhere('account_name','like',$query['account_name'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
            }
        }

        return Account::all();

    }
}
