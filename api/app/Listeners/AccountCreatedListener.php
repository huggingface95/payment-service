<?php

namespace App\Listeners;

use App\Events\AccountCreatedEvent;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\IbanIndividualActivationJob;
use App\Models\Traits\EmailPrepare;
use App\Traits\ReplaceRegularExpressions;

class AccountCreatedListener
{
    use EmailPrepare, ReplaceRegularExpressions;

    /**
     * @throws GraphqlException
     */
    public function handle(AccountCreatedEvent $event): void
    {
        $account = $event->account;

        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank', 'paymentSystem', 'currencies', 'groupRole'
        );

        if ($account->account_number == null){
            dispatch(new IbanIndividualActivationJob($account));
        }

        $messageData = $this->getTemplateContentAndSubject($account);
        $smtp = $this->getSmtp($account, $messageData['emails']);
        $this->sendEmail($smtp, $messageData);
    }
}
