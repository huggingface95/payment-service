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
    use ReplaceRegularExpressions;

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

        if ($account->account_number == null && $account->group->name == Groups::INDIVIDUAL) {
            dispatch(new IbanIndividualActivationJob($account));

            $account->account_state_id = AccountState::AWAITING_ACCOUNT;
            $account->save();
            $this->emailService->sendAccountStatusEmail($account);
        }
    }
}
