<?php

namespace App\DTO\Email\Request;

use App\Models\Account;

class EmailAccountMinMaxBalanceLimitRequestDTO
{
    public string $emailTemplateName;
    public Account $account;
    public object $data;
    public string $email;


    protected const ACCOUNT_MIN_LIMIT_COLUMN = 'account_min_balance';
    protected const ACCOUNT_MAX_LIMIT_COLUMN = 'account_max_balance';

    protected const MIN_LIMIT_TEMPLATE = 'Minimum balance limit has been reached';
    protected const MAX_LIMIT_TEMPLATE = 'Maximum balance limit has been reached';

    public static function transform(Account $account, bool $isMinLimit): self
    {
        $dto = new self();

        $account = Account::find($account->id);

        $data = [
            'account_id' => $account->id,
            'account_currency' => $account->currencies->name,
            'client_name' => $account->clientable->fullname,
            'customer_support_url' => $account->clientable->company->backoffice_support_url,
        ];

        if ($isMinLimit){
            $data[self::ACCOUNT_MIN_LIMIT_COLUMN] = $account->min_limit_balance;
        } else{
            $data[self::ACCOUNT_MAX_LIMIT_COLUMN] = $account->max_limit_balance;
        }


        $dto->account = $account;
        $dto->emailTemplateName = $isMinLimit ? self::MIN_LIMIT_TEMPLATE : self::MAX_LIMIT_TEMPLATE;
        $dto->email = $account->clientable->email;
        $dto->data = (object) $data;

        return $dto;
    }
}
