<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\AccountState;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\Auth;

class TransferOutgoingObserver extends BaseObserver
{
    public function creating(TransferOutgoing|BaseModel $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($applicant->id != $model->requested_by_id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        $this->checkAndCreateHistory($model, 'creating');

        return true;
    }

    public function updating(TransferOutgoing|BaseModel $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($applicant->id != $model->requested_by_id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        $this->checkAndCreateHistory($model, 'updating');

        return true;
    }
}
