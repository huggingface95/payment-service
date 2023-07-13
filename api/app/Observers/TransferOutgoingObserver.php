<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\AccountState;
use App\Models\ApplicantIndividual;
use App\Observers\Traits\AmountValidationTrait;
use App\Models\BaseModel;
use App\Models\Members;
use App\Models\PaymentSystem;
use App\Models\TransferOutgoing;
use App\Observers\Traits\AccessTransfersTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransferOutgoingObserver extends BaseObserver
{
    use AmountValidationTrait, AccessTransfersTrait;

    public function creating(TransferOutgoing|BaseModel|Model $model, bool $callHistory = false): bool
    {
        if (!parent::creating($model, $callHistory)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            $this->checkApplicantAccess($model, $applicant);
        }

        /** @var Members $member */
        if ($member = Auth::guard('api')->user()) {
            $this->checkMemberAccess($model, $member);
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }
        
        if ($model->paymentSystem->name != PaymentSystem::NAME_INTERNAL) {
            $checkRecipientCountry = $model->paymentSystem->regions()
                ->whereHas('countries', function ($query) use ($model) {
                    $query->where('id', $model->recipient_bank_country_id);
                })
                ->exists();
            
            if (!$checkRecipientCountry) {
                throw new GraphqlException('The payment system is not available for the recipient country', 'use');
            }
        }
        
        $this->checkAmountPositive($model);
        $this->checkAndCreateHistory($model, 'creating');

        return true;
    }

    public function updating(TransferOutgoing|BaseModel|Model $model, bool $callHistory = false): bool
    {
        if (!parent::updating($model, $callHistory)) {
            return false;
        }

        /** @var ApplicantIndividual $applicant */
        if ($applicant = Auth::guard('api_client')->user()) {
            $this->checkApplicantAccess($model, $applicant);
        }

        /** @var Members $member */
        if ($member = Auth::guard('api')->user()) {
            $this->checkMemberAccess($model, $member);
        }

        if ($model->account?->account_state_id != AccountState::ACTIVE) {
            throw new GraphqlException('Account must be active', 'use');
        }

        if ($model->paymentSystem->name != PaymentSystem::NAME_INTERNAL) {
            $checkRecipientCountry = $model->paymentSystem->regions()
                ->whereHas('countries', function ($query) use ($model) {
                    $query->where('id', $model->recipient_bank_country_id);
                })
                ->exists();
            
            if (!$checkRecipientCountry) {
                throw new GraphqlException('The payment system is not available for the recipient country', 'use');
            }
        }

        $this->checkAmountPositive($model);
        $this->checkAndCreateHistory($model, 'updating');

        return true;
    }
}
