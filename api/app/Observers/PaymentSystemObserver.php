<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;

class PaymentSystemObserver extends BaseObserver
{
    public function creating(PaymentSystem|BaseModel $model): bool
    {
        if (! parent::creating($model)) {
            return false;
        }

//        if ($model->name == PaymentSystem::NAME_INTERNAL) {
//            if (PaymentProvider::query()
//                ->join('companies', 'companies.id', '=', 'payment_provider.company_id')
//                ->join('payment_provider as p', 'p.company_id', '=', 'companies.id')
//                ->join('payment_system', 'payment_system.payment_provider_id', '=', 'p.id')
//                ->where('payment_system.name', '=', PaymentSystem::NAME_INTERNAL)
//                ->where('p.name', '=', PaymentSystem::NAME_INTERNAL)
//                ->where('payment_provider.id', '=', $model->payment_provider_id)->exists()) {
//                throw new GraphqlException('Payment System internal is already in company');
//            }
//        }

        return true;
    }
}
