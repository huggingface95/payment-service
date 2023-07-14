<?php

namespace App\Observers;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\AccountState;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\TransferIncoming;
use App\Observers\Traits\AccessTransfersTrait;
use App\Observers\Traits\AmountValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransferIncomingObserver extends BaseObserver
{
    use AmountValidationTrait, AccessTransfersTrait;

    public function creating(TransferIncoming|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        /** @var Members $member */
        if ($member = Auth::guard('api')->user()) {
            $this->checkMemberAccess($model, $member);
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

        /** @var Members $member */
        if ($member = Auth::guard('api')->user()) {
            $this->checkMemberAccess($model, $member);
        }
        
        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        $this->checkAmountPositive($model);
        $this->checkAndCreateHistory($model, 'updating');

        return true;
    }
}
