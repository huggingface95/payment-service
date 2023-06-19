<?php

namespace App\Observers;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\AccountState;
use App\Models\ApplicantIndividual;
use App\Models\TransferIncoming;
use App\Observers\Traits\AmountValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransferIncomingObserver extends BaseObserver
{
    use AmountValidationTrait;

    public function creating(TransferIncoming|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }
        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($model->recipient_type != ApplicantTypeEnum::INDIVIDUAL->toString() || $model->recipient_id != $applicant->id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        $this->checkAmountPositive($model);
        $this->checkAndCreateHistory($model, 'creating');

        return true;
    }

    public function updating(TransferIncoming|Model $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }
        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            if ($model->recipient_type != ApplicantTypeEnum::INDIVIDUAL->toString() || $model->recipient_id != $applicant->id) {
                throw new GraphqlException('requested_by_id must match id applicant', 'use');
            }
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        $this->checkAmountPositive($model);
        $this->checkAndCreateHistory($model, 'updating');

        return true;
    }
}
