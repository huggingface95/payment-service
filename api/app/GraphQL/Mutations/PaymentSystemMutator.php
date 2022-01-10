<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PaymentSystem;

class PaymentSystemMutator
{

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($_, array $args)
    {
        try {
            $paymentSystem = PaymentSystem::find($args['id']);
            $paymentSystem->delete();
            return $paymentSystem;
        } catch (\Exception $exception)
        {
            throw new GraphqlException('Payment system was not deleted. Please, check if it`s assigned to a Payment Provider.',"use");
        }

    }
}
