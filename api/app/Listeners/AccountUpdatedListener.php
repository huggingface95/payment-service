<?php

namespace App\Listeners;

use App\Events\AccountUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Traits\EmailPrepare;
use App\Traits\ReplaceRegularExpressions;

class AccountUpdatedListener
{
    use EmailPrepare, ReplaceRegularExpressions;

    /**
     * @throws GraphqlException
     */
    public function handle(AccountUpdatedEvent $event): void
    {
        $account = $event->account;

        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank', 'paymentSystem', 'currencies', 'groupRole'
        );

        if (array_key_exists('account_state_id', $account->getChanges())) {
            $messageData = $this->getTemplateContentAndSubject($account);
            $smtp = $this->getSmtp($account, $messageData['emails']);
            $this->sendEmail($smtp, $messageData);
        }
    }
}
