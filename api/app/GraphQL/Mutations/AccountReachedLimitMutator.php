<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\AccountReachedLimit;

class AccountReachedLimitMutator
{

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): AccountReachedLimit
    {

        $reachedLimit = new AccountReachedLimit($args);

        if (false === $this->compareWithCommissionLimit($reachedLimit)){
            throw new GraphqlException('limit does not match with commission limit',"use");
        }

        $reachedLimit->save();

        return $reachedLimit;
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): AccountReachedLimit
    {
        /** @var AccountReachedLimit $reachedLimit */
        $reachedLimit = AccountReachedLimit::find($args['id']);
        $reachedLimit = $reachedLimit->fill($args);

        if (false === $this->compareWithCommissionLimit($reachedLimit)){
            throw new GraphqlException('limit does not match with commission limit',"use");
        }

        $reachedLimit->save();

        return $reachedLimit;
    }

    private function compareWithCommissionLimit(AccountReachedLimit $reachedLimit): bool
    {
        foreach ($reachedLimit->account->commissionTemplate->commissionTemplateLimits as $limit){
            if ($reachedLimit->amount > $limit){
                return false;
            }
        }
        return true;
    }

}
