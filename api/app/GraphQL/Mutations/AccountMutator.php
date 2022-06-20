<?php

namespace App\GraphQL\Mutations;

use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Accounts;
use App\Models\Groups;

class AccountMutator
{

    public function create($root, array $args)
    {
        /** @var Accounts $account */
        $account = Accounts::create($args['input']);
        if ($args['input']['account_type'] == Accounts::PRIVATE)
            $account->applicantIndividual()->attach([$args['input']['client_id']]);
        elseif ($args['input']['account_type'] == Accounts::BUSINESS)
            $account->applicantCompany()->attach([$args['input']['client_id']]);

        return $account;
    }

    public function update($root, array $args)
    {
        /** @var Accounts $account */
        $account = Accounts::find($args['id']);
        if ($args['input']['account_type'] == Accounts::PRIVATE)
            $account->applicantIndividual()->detach([$args['input']['client_id']]);
        elseif ($args['input']['account_type'] == Accounts::BUSINESS)
            $account->applicantCompany()->detach([$args['input']['client_id']]);

        $account->update($args);

        return $account;
    }

    public function generate($root, array $args)
    {
        $account = Accounts::find($args['id']);

        dispatch(new IbanIndividualActivationJob($account));
    }
}
