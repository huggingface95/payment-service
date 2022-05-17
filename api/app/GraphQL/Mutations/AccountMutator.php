<?php

namespace App\GraphQL\Mutations;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Jobs\IbanActivationJob;
use App\Models\Accounts;

class AccountMutator
{

    public function generate($root, array $args)
    {
        try {
            $account = Accounts::find($args['id']);

            dispatch(new IbanActivationJob($account));
        }
        catch (\Throwable $e){
            dd($e);
        }
    }
}
