<?php

namespace App\DTO\Transfer;

use App\Enums\ClientTypeEnum;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateTransferBetweenUsersDTO
{
    public CreateTransferBetweenUsersOutgoingDTO $betweenUsersOutgoingDTO;
    public CreateTransferBetweenUsersIncomingDTO $betweenUsersIncomingDTO;

    protected static array $general;

    public static function transformWithChildren(array $args, Account $fromAccount, Account $toAccount, int $operationType): self
    {
        self::prepareGeneralData($fromAccount, $toAccount, $operationType);
        $dto = new self();
        $dto->betweenUsersOutgoingDTO = CreateTransferBetweenUsersOutgoingDTO::transform($args);
        $dto->betweenUsersIncomingDTO = CreateTransferBetweenUsersIncomingDTO::transform($args);

        return $dto;
    }

    public function toArray(): array
    {
        return (array) $this;
    }

    protected static function getGeneralData(string $selected = null, string $removed = null): array
    {
        $response = [];
        if (!empty(self::$general)) {
            if ($selected && array_key_exists($selected, self::$general)) {
                $response = array_merge($response, self::$general[$selected]);
            }
            foreach (self::$general as $k => $v) {
                if (!in_array($k, [$selected, $removed])) {
                    $response[$k] = $v;
                }
            }
        }
        return $response;
    }

    private static function prepareGeneralData(Account $from, Account $to, int $operationType): void
    {
        $date = Carbon::now();
        $type = Auth::guard('api')->check() ? ClientTypeEnum::MEMBER : ClientTypeEnum::APPLICANT;
        $id = Auth::guard('api')->check() ? 1 : Auth::guard('api_client')->user()?->id;
        self::$general = [
            CreateTransferBetweenUsersIncomingDTO::class => [
                'account_id' => $to->id,
                'currency_id' => $to->currencies?->id,
            ],
            CreateTransferBetweenUsersOutgoingDTO::class => [
                'currency_id' => $from->currencies?->id,
                'account_id' => $from->id,
                'requested_by_id' => $id
            ],
            'company_id' => $from->company_id,
            'payment_provider_id' => $from->company->paymentProviderInternal()->first()?->id,
            'payment_system_id' => $from->company->paymentSystemInternal()->first()?->id,
            'created_at' => $date->format('Y-m-d H:i:s'),
            'execution_at' => $date->format('Y-m-d H:i:s'),
            'payment_number' => 'BTW' . rand(),
            'operation_type_id' => $operationType,
            'recipient_id' => $id,
            'recipient_type' => $type == ClientTypeEnum::MEMBER ? class_basename(ApplicantCompany::class) : class_basename(ApplicantIndividual::class),
            'sender_id' => $id,
            'sender_type' => $type == ClientTypeEnum::MEMBER ? class_basename(ApplicantCompany::class) : class_basename(ApplicantIndividual::class),
            'user_type' => $type == ClientTypeEnum::MEMBER ? class_basename(Members::class) : class_basename(ApplicantIndividual::class),
        ];
    }
}
