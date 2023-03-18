<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use App\Models\Traits\CheckForEvents;
use Illuminate\Support\Facades\Auth;

class BaseObserver
{
    use CheckForEvents;

    /**
     * @throws GraphqlException
     */
    public function creating(BaseModel $model): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        return self::filterByPermissionFilters($user, 'creating', $model)
            && self::filterByRoleActions($user, 'creating', $model)
            && self::filterByCompany($user, 'creating', $model)
            && self::checkSoftDeletedRecord('creating', $model);
    }

    /**
     * @throws GraphqlException
     */
    public function saving(BaseModel $model): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        return self::filterByPermissionFilters($user, 'saving', $model)
            && self::filterByRoleActions($user, 'saving', $model)
            && self::filterByCompany($user, 'saving', $model)
            && self::checkSoftDeletedRecord('saving', $model);
    }

    /**
     * @throws GraphqlException
     */
    public function updating(BaseModel $model): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        return self::filterByPermissionFilters($user, 'updating', $model)
            && self::filterByRoleActions($user, 'updating', $model)
            && self::filterByCompany($user, 'updating', $model)
            && self::checkSoftDeletedRecord('updating', $model);
    }

    /**
     * @throws GraphqlException
     */
    public function deleting(BaseModel $model): bool
    {
        /** @var Members|ApplicantIndividual $user */
        $user = Auth::user();

        return self::filterByPermissionFilters($user, 'deleting', $model)
            && self::filterByRoleActions($user, 'deleting', $model)
            && self::filterByCompany($user, 'deleting', $model);
    }
}
