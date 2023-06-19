<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\RegionCountry;

class RegionCountryObserver
{
    public function saving(RegionCountry $model): bool
    {
        $company = Company::query()->findOrFail($model->pivotParent->company_id);

        if ($company->country_id != $model->country_id) {
            return false;
        }

        return true;
    }

    public function creating(RegionCountry $model): bool
    {
        $company = Company::query()->findOrFail($model->pivotParent->company_id);

        if ($company->country_id != $model->country_id) {
            return false;
        }

        return true;
    }
}
