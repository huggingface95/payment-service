<?php

namespace App\DTO\Email\Request;

use App\Models\Account;

class EmailAccountMinMaxBalanceLimitRequestDTO
{
    public string $emailTemplateName;

    public int $companyId;

    public Account $account;

    public object $data;

    public string $email;

    public const MEMBER = 'member';

    public const INDIVIDUAL = 'individual';

    protected const ACCOUNT_MIN_LIMIT_COLUMN = 'account_min_balance';

    protected const ACCOUNT_MAX_LIMIT_COLUMN = 'account_max_balance';

    protected const MIN_INDIVIDUAL_LIMIT_TEMPLATE = 'Minimum balance limit has been reached';

    protected const MAX_INDIVIDUAL_LIMIT_TEMPLATE = 'Maximum balance limit has been reached';

    protected const MIN_MEMBER_LIMIT_TEMPLATE = 'Minimum balance limit has been reached for client';

    protected const MAX_MEMBER_LIMIT_TEMPLATE = 'Maximum balance limit has been reached for client';

    public static function transform(Account $account, bool $isMinLimit, string $type = self::INDIVIDUAL): self
    {
        $dto = new self();

        $data = [
            'account_id' => $account->id,
            'account_currency' => $account->currencies->name,
            'client_name' => $account->clientable->fullname,
        ];

        if ($type == self::INDIVIDUAL) {
            $data['customer_support_url'] = $account->clientable->company->backoffice_support_url;
            $templateName = $isMinLimit ? self::MIN_INDIVIDUAL_LIMIT_TEMPLATE : self::MAX_INDIVIDUAL_LIMIT_TEMPLATE;
            $email = $account->clientable->email;
        } else {
            $data['customer_support_url'] = $account->member->company->backoffice_support_url;
            $data['name'] = $account->member->fullname;
            $templateName = $isMinLimit ? self::MIN_MEMBER_LIMIT_TEMPLATE : self::MAX_MEMBER_LIMIT_TEMPLATE;
            $email = $account->member->email;
        }

        if ($isMinLimit) {
            $data[self::ACCOUNT_MIN_LIMIT_COLUMN] = $account->min_limit_balance;
        } else {
            $data[self::ACCOUNT_MAX_LIMIT_COLUMN] = $account->max_limit_balance;
        }

        $dto->account = $account;
        $dto->emailTemplateName = $templateName;
        $dto->companyId = $account->company_id;
        $dto->email = $email;
        $dto->data = (object) $data;

        return $dto;
    }
}
