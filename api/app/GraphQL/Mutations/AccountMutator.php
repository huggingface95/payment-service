<?php

namespace App\GraphQL\Mutations;

use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Accounts;
use App\Models\Groups;
use Illuminate\Support\Facades\Auth;

class AccountMutator
{
    public function create($root, array &$args): Accounts
    {
        $args['member_id'] = Auth::user()->id;
        /** @var Accounts $account */
        $account = Accounts::create($args['input']);
        $args['account_type'] = $this->setAccountType($args['input']['group_type_id']);

        return $account;
    }

    public function update($root, array $args): Accounts
    {
        /** @var Accounts $account */
        $account = Accounts::find($args['id']);
        $args['account_type'] = $this->setAccountType($args['input']['group_type_id']);

        $account->update($args);

        return $account;
    }

    public function generate($root, array $args): void
    {
        $account = Accounts::find($args['id']);

        dispatch(new IbanIndividualActivationJob($account));
    }

    protected function setAccountType(int $groupId)
    {
        if ($groupId== Groups::INDIVIDUAL) {
            return Accounts::PRIVATE;
        } elseif ($groupId == Groups::COMPANY) {
            return Accounts::BUSINESS;
        }
    }
}
