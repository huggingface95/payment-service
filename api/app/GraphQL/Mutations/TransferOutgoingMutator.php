<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferOutgoingChannelEnum;
use App\Models\TransferOutgoing;

class TransferOutgoingMutator extends BaseMutator
{
    public function create($root, array $args): TransferOutgoing
    {
        $args['user_type'] = class_basename(Members::class);
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::PENDING->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = OperationTypeEnum::OUTGOING_TRANSFER->value;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['sender_id'] = 1;
        $args['sender_type'] = class_basename(ApplicantCompany::class);
        $args['system_message'] = 'test';
        $args['channel'] = TransferOutgoingChannelEnum::BACK_OFFICE->toString();
        $args['reason'] = 'test';
        $args['recipient_country_id'] = 1;
        $args['respondent_fees_id'] = 1;

        $transfer = TransferOutgoing::create($args);

        return $transfer;
    }
}
