<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Account;
use App\Models\AccountState;
use App\Models\GroupRole;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AccountMutator
{
    use ReplaceRegularExpressions;

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): LengthAwarePaginator
    {
        $args['member_id'] = Auth::user()->id;

        $args['account_type'] = $this->setAccountType($args['group_type_id']);
        if (! isset($args['account_number'])) {
            $args['account_state_id'] = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
        } else {
            $args['account_state_id'] = AccountState::WAITING_FOR_APPROVAL;
        }

        Account::create($args);

        if (isset($args['query'])) {
            return Account::getAccountFilter($args['query'])->paginate(env('PAGINATE_DEFAULT_COUNT'));
        } else {
            return Account::paginate(env('PAGINATE_DEFAULT_COUNT'));
        }
    }

    public function generate($root, array $args): void
    {
        $account = Account::find($args['id']);
        $account->account_state_id = AccountState::AWAITING_ACCOUNT;
        $account->save();

        dispatch(new IbanIndividualActivationJob($account));
    }

    protected function setAccountType(int $groupId)
    {
        if ($groupId == GroupRole::INDIVIDUAL) {
            return Account::PRIVATE;
        } elseif ($groupId == GroupRole::COMPANY) {
            return Account::BUSINESS;
        }
    }
}
