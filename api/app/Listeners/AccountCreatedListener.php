<?php

namespace App\Listeners;

use App\Events\AccountUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Traits\EmailPrepare;
use App\Traits\ReplaceRegularExpressions;

class AccountCreatedListener
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

        $smtp = $this->getSmtp($account);
        $messageData = $this->getTemplateContentAndSubject($account);
        $this->sendEmail($smtp, $messageData);
    }
}
