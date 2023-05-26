<?php

namespace App\Observers;

use App\Models\BaseModel;
use App\Models\PaymentSystem;
use Illuminate\Database\Eloquent\Model;

class PaymentSystemObserver extends BaseObserver
{
    public function creating(PaymentSystem|BaseModel|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
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

        $this->checkAndCreateHistory($model, 'creating');

        return true;
    }
}
