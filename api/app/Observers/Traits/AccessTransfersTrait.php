<?php

namespace App\Observers\Traits;

use App\Enums\ApplicantTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;

trait AccessTransfersTrait
{

    public function checkApplicantAccess(TransferOutgoing $model, ApplicantIndividual $applicant): void
    {
        $isAllowToAccess = ($model->account?->owner_id == $applicant->id) ||
            ($model->account?->client_id == $applicant->id && $model->account?->client_type == ApplicantTypeEnum::INDIVIDUAL->toString());

        if (!$isAllowToAccess) {
            throw new GraphqlException('The account must belong to the applicant', 'use');
        }

        if ($applicant->id != $model->requested_by_id && !$isAllowToAccess) {
            throw new GraphqlException('The transfer must belong to the applicant', 'use');
        }
    }

    public function checkMemberAccess(TransferOutgoing|TransferIncoming $model, Members $member): void
    {
        $originalData = $model->getOriginal();

        if (empty($originalData)) {
            $originalData = $model->toArray();
        }

        $isMemberOwner = $member->id == $originalData['requested_by_id'] && $originalData['user_type'] == class_basename(Members::class);
        if ($isMemberOwner) {
            return;
        }

        $isAllowToAccess = $isMemberOwner ||
            ($member->company_id == $originalData['company_id'] && $originalData['user_type'] != class_basename(Members::class));

        if (!$isAllowToAccess) {
            throw new GraphqlException('The transfer must belong to the member or applicant from the same member company', 'use');
        }

        if ($model->status_id == $originalData['status_id']) {
            throw new GraphqlException('Member cannot Edit applicant\'s transfers', 'use');
        }

        if ($originalData['status_id'] != PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer must be in the Unsigned status', 'use');
        }
    }

}
