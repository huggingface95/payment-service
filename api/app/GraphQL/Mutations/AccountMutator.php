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
        $account = Accounts::create($args);
        if ($args['client_type'] == Groups::INDIVIDUAL)
            $account->applicantIndividual()->attach([$args['client_id']]);
        elseif ($args['client_type'] == Groups::COMPANY)
            $account->applicantCompany()->attach([$args['client_id']]);

        return $account;
    }

    public function update($root, array $args)
    {
        /** @var Accounts $account */
        $account = Accounts::find($args['id']);
        if ($args['client_type'] == Groups::INDIVIDUAL)
            $account->applicantIndividual()->detach([$args['client_id']]);
        elseif ($args['client_type'] == Groups::COMPANY)
            $account->applicantCompany()->detach([$args['client_id']]);

        $account->update($args);

        return $account;
    }

    public function generate($root, array $args)
    {
        $account = Accounts::find($args['id']);

        dispatch(new IbanIndividualActivationJob($account));
    }
}
