<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\TransferBetween;
use App\Models\TransferIncoming;
use Illuminate\Database\Eloquent\Model;

class TransferBetweenObserver extends BaseObserver
{
    public function creating(TransferBetween|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        $countryId = $model->transferIncoming->account->owner->country_id;
        $model->transferOutgoing->company->paymentSystemInternal->regions->load('countries');
        $found = $model->transferOutgoing->company->paymentSystemInternal->regions->map(function ($region) use ($countryId) {
            return $region->countries->contains('id', $countryId);
        })->contains(true);

        if (!$found) {
            throw new GraphqlException('The payment system is not available for the country of the account owner', 'use');
        }

        return true;
    }

    public function updating(TransferIncoming|Model $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }

        $countryId = $model->transferIncoming->account->owner->country_id;
        $model->transferOutgoing->company->paymentSystemInternal->regions->load('countries');
        $found = $model->transferOutgoing->company->paymentSystemInternal->regions->map(function ($region) use ($countryId) {
            return $region->countries->contains('id', $countryId);
        })->contains(true);

        if (!$found) {
            throw new GraphqlException('The payment system is not available for the country of the account owner', 'use');
        }

        return true;
    }
}
