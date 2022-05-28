<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Jobs\Redis\PaymentJob;
use App\Models\AccountLimit;
use App\Models\Accounts;
use App\Models\ApplicantIndividual;
use App\Models\CommissionTemplateLimit;
use App\Models\Groups;
use App\Models\Payments;

class PaymentsMutator
{

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @throws GraphqlException
     */

    public function create($root, array $args)
    {
        $memberId = Payments::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;

        if (false === $this->checkLimit($args['account_id'], $args['amount'])){
            throw new GraphqlException('limit is exceeded',"use");
        }

        $payment = Payments::create($args);
        dispatch(new PaymentJob($payment));

        return $payment;
    }

    public function update($_, array $args)
    {
        $payment = Payments::find($args['id']);
        $memberId = Payments::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
        $payment->update($args);
        return $payment;
    }

    private function checkLimit(int $accountId, float $amount): bool
    {
        /** @var Accounts $account */
        $account = Accounts::with('limits', 'commissionTemplate.commissionTemplateLimits')->findOrFail($accountId);

        /** @var AccountLimit | CommissionTemplateLimit $reachedLimit */
        $reachedLimit = collect([$account->limits, $account->commissionTemplate->commissionTemplateLimit])->flatten(1)
            ->filter(function ($limit) use ($amount){return !($amount <= $limit->amount);})
            ->sortBy([fn ($a, $b) => $a instanceof AccountLimit < $b instanceof AccountLimit])
            ->first();


        if ($reachedLimit){
            $account->reachedLimits()->create([
                'group_type' => $account->clientable instanceof ApplicantIndividual ? Groups::INDIVIDUAL : Groups::COMPANY,
                'client_name' => $account->clientable->fullname ?? $account->clientable->name,
                'client_state' => $account->clientable->state->name,
                'transfer_direction' => $reachedLimit->commissionTemplateLimitTransferDirection->name,
                'limit_type' => $reachedLimit->commissionTemplateLimitType->name,
                'limit_value' => $reachedLimit->commissionTemplateLimitPeriod->name,
                'limit_currency' => $reachedLimit->currency->name,
                'period' => $reachedLimit->period_count,
                'amount' => $reachedLimit->amount,
            ]);
            return false;
        }
        return true;
    }
}
