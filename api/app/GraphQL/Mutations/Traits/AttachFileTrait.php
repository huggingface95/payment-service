<?php

namespace App\GraphQL\Mutations\Traits;

use App\Exceptions\GraphqlException;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;

trait AttachFileTrait
{
    /**
     * @throws GraphqlException
     */
    public function attachFile($root, array $args): TransferOutgoing | TransferIncoming | TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']) ?? throw new GraphqlException('Transfer not found');

        $this->transferService->attachFileById($transfer, $args['file_id']);

        return $transfer;
    }
}
