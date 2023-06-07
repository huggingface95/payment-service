<?php

namespace App\Observers\Traits;

use App\Exceptions\GraphqlException;
use Illuminate\Database\Eloquent\Model;

trait AmountValidationTrait
{
    public function checkAmountPositive(Model $model): bool
    {
        if ($model->amount < 0) {
            throw new GraphqlException('Amount must be greater than 0', 'use');
        }

        return true;
    }
}
