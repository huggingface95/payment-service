<?php

namespace App\Observers;

use App\Models\AccountState;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\CompanyModule;
use Illuminate\Database\Eloquent\Model;

class CompanyModuleObserver extends BaseObserver
{

    public function updated(CompanyModule|Model $model, bool $callHistory = true): bool
    {
        parent::updated($model, $callHistory);

        $model->load(['company.applicantIndividuals', 'company.applicantCompanies']);
        $clients = $model->company->applicantIndividuals->merge($model->company->applicantCompanies)
            ->filter(function (ApplicantIndividual|ApplicantCompany $c) {
                return $c && $c->account;
            });

        if ($model->is_active === false) {
            $clients->each(function (ApplicantIndividual|ApplicantCompany $client) {
                $client->account->update(['account_state_id' => AccountState::SUSPENDED]);
            });
        } else {
            $clients->each(function (ApplicantIndividual|ApplicantCompany $client) {
                $client->account->restoreLast();
            });
        }

        return true;
    }

}
