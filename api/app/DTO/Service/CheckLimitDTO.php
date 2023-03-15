<?php

namespace App\DTO\Service;

use App\Models\Account;
use App\Models\Payments;
use App\Models\TransferOutgoing;

class CheckLimitDTO
{
    public TransferOutgoing|Payments $object;
    public ?Account $account;
    public float $amount;

    public static function transform(TransferOutgoing|Payments $data, float $amount): self
    {
        $dto = new self();
        $dto->object = $data;
        $dto->account = Account::query()->with(['clientable', 'limits', 'commissionTemplate.commissionTemplateLimits'])->where('id', $data->account_id)->first();
        $dto->amount = $amount;
        return $dto;
    }
}
