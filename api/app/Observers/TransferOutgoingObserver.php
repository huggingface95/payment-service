<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\Auth;

class TransferOutgoingObserver extends BaseObserver
{
    public function creating(TransferOutgoing|BaseModel $model): bool
    {
        if (! parent::creating($model)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($applicant->id != $model->requested_by_id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        return true;
    }

    public function updating(TransferOutgoing|BaseModel $model): bool
    {
        if (! parent::updating($model)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($applicant->id != $model->requested_by_id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        return true;
    }
}
