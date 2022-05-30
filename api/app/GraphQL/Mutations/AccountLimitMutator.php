<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\AccountLimit;
use App\Models\CommissionTemplateLimit;

class AccountLimitMutator
{

    /**
     * @throws GraphqlException
     */
    public function create($root, array $args): AccountLimit
    {
        $limit = new AccountLimit($args);

        if (false === $this->compareWithCommissionLimit($limit)){
            throw new GraphqlException('limit does not match with commission limit',"use");
        }

        $limit->save();

        return $limit;
    }

    /**
     * @throws GraphqlException
     */
    public function update($root, array $args): AccountLimit
    {
        /** @var AccountLimit $limit */
        $limit = AccountLimit::find($args['id']);
        $limit = $limit->fill($args);

        if (false === $this->compareWithCommissionLimit($limit)){
            throw new GraphqlException('limit does not match with commission limit',"use");
        }

        $limit->save();

        return $limit;
    }

    private function compareWithCommissionLimit(AccountLimit $limit): bool
    {
        /** @var CommissionTemplateLimit $commissionLimit */
        $commissionLimit = CommissionTemplateLimit::query()
            ->where('currency_id', $limit->currency_id)
            ->where('commission_template_limit_type_id', $limit->commission_template_limit_type_id)
            ->where('commission_template_limit_transfer_direction_id', $limit->commission_template_limit_transfer_direction_id)
            ->where('commission_template_limit_period_id', $limit->commission_template_limit_period_id)
            ->where('commission_template_limit_action_type_id', $limit->commission_template_limit_action_type_id)
            ->first();

        return $commissionLimit->amount >= $limit->amount;
    }

}
