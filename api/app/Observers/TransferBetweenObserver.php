<?php

namespace App\Observers;

use App\Models\TransferBetween;
use Illuminate\Database\Eloquent\Model;

class TransferBetweenObserver extends BaseObserver
{
    public function creating(TransferBetween|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        return true;
    }

    public function updating(TransferBetween|Model $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }

        return true;
    }
}
