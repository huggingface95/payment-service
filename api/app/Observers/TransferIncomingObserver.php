<?php

namespace App\Observers;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\TransferIncoming;
use Illuminate\Support\Facades\Auth;

class TransferIncomingObserver extends BaseObserver
{
    public function creating(TransferIncoming|BaseModel $model): bool
    {
        if (! parent::creating($model)) {
            return false;
        }
        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($model->recipient_type != ApplicantTypeEnum::INDIVIDUAL->toString() || $model->recipient_id != $applicant->id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        return true;
    }

    public function updating(TransferIncoming|BaseModel $model): bool
    {
        if (! parent::updating($model)) {
            return false;
        }
        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($model->recipient_type != ApplicantTypeEnum::INDIVIDUAL->toString() || $model->recipient_id != $applicant->id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        return true;
    }
}
