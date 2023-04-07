<?php

namespace App\GraphQL\Queries;

use App\Models\Account;
use App\Models\AccountClient;
use App\Models\AccountState;
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
            $list->where(function (Builder $q) use ($args) {
                $q->whereHas('individual', function (Builder $q) use ($args) {
                    $q->where('company_id', $args['company_id']);
                })->orWhereHas('company', function (Builder $q) use ($args) {
                    $q->where('company_id', $args['company_id']);
                });
            });
        }

        if (isset($args['group_role_id'])) {
            $list->where(function (Builder $q) use ($args) {
                $q->whereHas('individualGroupRole', function (Builder $q) use ($args) {
                    $q->where('id', $args['group_role_id']);
                })->orWhereHas('companyGroupRole', function (Builder $q) use ($args) {
                    $q->where('id', $args['group_role_id']);
                });
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

    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function accountActiveList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): \Illuminate\Database\Eloquent\Collection|array
    {
        $list = Account::query()->where('account_state_id','=',AccountState::ACTIVE);
        if (isset($args['client_id'])) {
            $list = Account::query()->join('account_clients', 'accounts.id', '=', 'account_clients.client_id')
                ->where('account_clients.client_id', '=', $args['client_id']['id'])
                ->where('account_clients.client_type', '=', $args['client_id']['client_type'])
                ->where('accounts.account_state_id','=',AccountState::ACTIVE);
        }

        return $list->get();
    }
}
