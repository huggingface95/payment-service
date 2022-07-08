<?php

namespace App\GraphQL\Queries;

use App\Models\AccountClient;
use App\Models\Accounts;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use App\Models\GroupType;
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

        return Accounts::paginate($args['paginate']['count']);
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
}
