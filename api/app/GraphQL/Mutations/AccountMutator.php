<?php

namespace App\GraphQL\Mutations;

use App\Jobs\IbanActivationJob;
use App\Models\Accounts;

class AccountMutator
{

    public function generate($root, array $args)
    {
        $account = Accounts::find($args['id']);

        dispatch(new IbanActivationJob($account));
    }
}
