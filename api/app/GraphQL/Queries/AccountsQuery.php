<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Handlers\FilterConditionsHandler;
use App\Models\Account;
use App\Models\AccountState;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AccountsQuery
{
    public function __construct(
        protected FilterConditionsHandler $handler
    ) {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function paginate($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $condition = ['company_id' => $args['company_id'], 'group_role_id' => $args['group_role_id'], 'group_type_id' => $args['group_type_id']];

        return Account::paginate($args['paginate']['count']);
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function clientList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        $list = Account::query()->whereHas('clientable')->with('clientable');

        if (isset($args['filter'])) {
            $this->handler->__invoke($list, $args['filter']);
        }

        return $list->get()->pluck('clientable')->unique();
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

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function accountActiveList($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): \Illuminate\Database\Eloquent\Collection|array
    {
        $list = Account::query()->where('account_state_id', '=', AccountState::ACTIVE);
        if (isset($args['client_id'])) {
            $list = Account::query()->join('account_clients', 'accounts.id', '=', 'account_clients.client_id')
                ->where('account_clients.client_id', '=', $args['client_id']['id'])
                ->where('account_clients.client_type', '=', $args['client_id']['client_type'])
                ->where('accounts.account_state_id', '=', AccountState::ACTIVE);
        }

        return $list->get();
    }
}
