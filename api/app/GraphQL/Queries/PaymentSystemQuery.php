<?php

namespace App\GraphQL\Queries;

use App\Models\PaymentSystem;

final class PaymentSystemQuery
{
    /**
     * Get data with pagination and filteration
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args)
    {
        $paymentSystems = PaymentSystem::get()->unique('name');

        return $paymentSystems;
    }
}
