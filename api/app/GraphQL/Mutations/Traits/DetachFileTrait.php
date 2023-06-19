<?php

namespace App\GraphQL\Mutations\Traits;

use App\Exceptions\GraphqlException;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;

trait DetachFileTrait
{
    /**
     * @throws GraphqlException
     */
    public function detachFile($root, array $args): TransferOutgoing | TransferIncoming | TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']) ?? throw new GraphqlException('Transfer not found');

        $this->transferService->detachFileById($transfer, $args['file_id']);

        return $transfer;
    }
}
