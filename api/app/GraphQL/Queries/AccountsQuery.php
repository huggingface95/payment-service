<?php

namespace App\GraphQL\Queries;

use App\Models\Account;
use App\Models\AccountClient;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AccountsQuery
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id' => $args['company_id'], 'group_role_id' => $args['group_role_id'], 'group_type_id' => $args['group_type_id']];

        return Account::paginate($args['paginate']['count']);
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function clientList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): \Illuminate\Database\Eloquent\Collection|array
    {
        $list = AccountClient::query();

        if (isset($args['group_type'])) {
            if ($args['group_type'] == GroupRole::INDIVIDUAL) {
                $list->where('client_type', class_basename(ApplicantIndividual::class));
            } else {
                $list->where('client_type', class_basename(ApplicantCompany::class));
            }
        }

        if (isset($args['company_id'])) {
            $list->whereHas('individual', function (Builder $q) use ($args) {
                $q->where('company_id', $args['company_id']);
            })->orWhereHas('company', function (Builder $q) use ($args) {
                $q->where('company_id', $args['company_id']);
            });
        }


        return $list->get();
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
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
}
