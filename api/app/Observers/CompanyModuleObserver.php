<?php

namespace App\Observers;

use App\Jobs\UpdateOrRestoreAccountStateJob;
use App\Models\CompanyModule;
use Illuminate\Database\Eloquent\Model;

class CompanyModuleObserver extends BaseObserver
{

    public function updated(CompanyModule|Model $model, bool $callHistory = true): bool
    {
        parent::updated($model, $callHistory);

        $model->load(['company.accounts.clientable']);
        //TODO remove filter when createAccount client_id required
        $accounts = $model->company->accounts->filter(function ($a) {
            return $a->clientable;
        });


        /*   may be needed in the future */
//        $dispatcher = Account::getEventDispatcher();
//        $payload = $dispatcher->getListeners('eloquent.updating: ' . Account::class);
//        //disable updating event
//        $dispatcher->forget('eloquent.updating: ' . Account::class);
//        //enable updating event
//        $dispatcher->push('eloquent.updating: ' . Account::class, $payload);

        dispatch(new UpdateOrRestoreAccountStateJob($model, $accounts));

        return true;
    }

}
