<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferOutgoingChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Support\Carbon;

class TransferOutgoingMutator extends BaseMutator
{
    public function __construct(
        protected TransferOutgoingService $transferService,
        protected AccountRepository $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferOutgoing
    {
        $date = Carbon::now();
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
        $args['created_at'] = $date->format('Y-m-d H:i:s');

        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
            $args['status_id'] = PaymentStatusEnum::WAITING_EXECUTION_DATE->value;
        }

        $transfer = TransferOutgoing::create($args);

        return $transfer;
    }

    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->validateUpdateTransferStatus($transfer, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::ERROR->value:
            case PaymentStatusEnum::CANCELED->value:
                $this->transferService->updateTransferStatusToCancelOrError($transfer, $args['status_id']);

                break;
            case PaymentStatusEnum::SENT->value:
                $this->transferService->updateTransferStatusToSent($transfer);

                break;
            default:
                $this->transferRepository->update($transfer, ['status_id' => $args['status_id']]);

                break;
        }

        return $transfer;
    }
}
