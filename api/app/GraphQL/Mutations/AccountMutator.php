<?php

namespace App\GraphQL\Mutations;

use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Accounts;
use App\Models\AccountState;
use App\Models\GroupRole;
use Illuminate\Support\Facades\Auth;

class AccountMutator
{
    public function create($root, array $args): Accounts
    {
        $args = $args['input'];
        $args['member_id'] = Auth::user()->id;

        /** @var Accounts $account */
        $args['account_type'] = $this->setAccountType($args['group_type_id']);
        if (!isset($args['account_number']))
        {
            $args['account_state_id'] = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
        } else {
            $args['account_state_id'] = AccountState::WAITING_FOR_APPROVAL;
        }

        return Accounts::create($args);
    }

    public function update($root, array $args): Accounts
    {
        $args = $args['input'];
        /** @var Accounts $account */
        $account = Accounts::find($args['id']);
        $args['account_type'] = $this->setAccountType($args['group_type_id']);

        $account->update($args);

        return $account;
    }

    public function generate($root, array $args): void
    {
        $account = Accounts::find($args['id']);
        $account->account_state_id = AccountState::AWAITING_ACCOUNT;
        $account->save();

        dispatch(new IbanIndividualActivationJob($account));
    }

    protected function setAccountType(int $groupId)
    {
        if ($groupId == GroupRole::INDIVIDUAL) {
            return Accounts::PRIVATE;
        } elseif ($groupId == GroupRole::COMPANY) {
            return Accounts::BUSINESS;
        }
    }
}
