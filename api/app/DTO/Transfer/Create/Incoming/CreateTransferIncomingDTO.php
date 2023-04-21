<?php

namespace App\DTO\Transfer\Create\Incoming;

class CreateTransferIncomingDTO
{
    public int $company_id;
    public int $group_id;
    public int $urgency_id;
    public int $operation_type_id;
    public int $group_type_id;
    public int $project_id;
    public string $amount;
    public string $payment_number;
    public string $amount_debt;
    public int $currency_id;
    public int $account_id;
    public int $payment_provider_id;
    public int $payment_system_id;
    public int $payment_bank_id;
    public int $price_list_id;
    public int $price_list_fee_id;
    public int $recipient_id;
    public int $respondent_fees_id;
    public string $recipient_type;
    public string $system_message;
    public string $reason;
    public string $channel;
    public string $sender_account;
    public string $sender_bank_name;
    public string $sender_bank_address;
    public string $sender_bank_swift;
    public int $sender_bank_country_id;
    public string $sender_name;
    public int $sender_country_id;
    public string $sender_city;
    public string $sender_address;
    public string $sender_zip;
    public string $bank_message;
    public array $file_id;
    public string $transfer_swift;
    public int $status_id;
    public string $execution_at;

    protected function __construct(array $args)
    {
        $properties = collect((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC))->pluck('name')->toArray();

        foreach ($args as $k => $v) {
            if (in_array($k, $properties)) {
                $this->{$k} = $v;
            }
        }
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}
