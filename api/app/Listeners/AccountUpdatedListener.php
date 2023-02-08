<?php

namespace App\Listeners;

use App\Events\AccountUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Services\EmailService;

class AccountUpdatedListener
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @throws GraphqlException
     */
    public function handle(AccountUpdatedEvent $event): void
    {
        $account = $event->account;

        if (array_key_exists('account_state_id', $account->getChanges())) {
            $this->emailService->sendAccountStatusEmail($account);
        }
    }
}
