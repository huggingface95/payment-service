<?php

namespace App\GraphQL\Mutations;

use App\Jobs\Redis\PaymentJob;
use App\Models\Payments;

class PaymentsMutator
{

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */

    public function create($root, array $args)
    {
        $memberId = Payments::DEFAULT_MEMBER_ID;
        $args['member_id'] = $memberId;
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
}
