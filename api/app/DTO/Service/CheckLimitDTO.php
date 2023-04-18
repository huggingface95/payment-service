<?php

namespace App\DTO\Service;

use App\Enums\ClientTypeEnum;
use App\Models\Account;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\Auth;

class CheckLimitDTO
{
    public TransferOutgoing $object;
    public ?Account $account;
    public float $amount;
    public string $clientType;

    public static function transform(TransferOutgoing $data, float $amount): self
    {
        $dto = new self();
        $dto->object = $data;
        $dto->account = Account::query()->with(['clientable', 'limits', 'commissionTemplate.commissionTemplateLimits'])->where('id', $data->account_id)->first();
        $dto->amount = $amount;
        $dto->clientType = Auth::guard('api')->check() ? ClientTypeEnum::MEMBER->name : ClientTypeEnum::APPLICANT->name;

        return $dto;
    }
}
