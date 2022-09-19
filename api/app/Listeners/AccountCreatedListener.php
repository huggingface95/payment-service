<?php

namespace App\Listeners;

use App\Events\AccountCreatedEvent;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\AccountState;
use App\Models\Groups;
use App\Services\EmailService;
use App\Traits\ReplaceRegularExpressions;

class AccountCreatedListener
{

    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @throws GraphqlException
     */
    public function handle(AccountCreatedEvent $event): void
    {
        $account = $event->account;

        $this->emailService->sendAccountStatusEmail($account);
    }
}
