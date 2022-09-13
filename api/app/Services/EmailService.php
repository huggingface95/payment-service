<?php

namespace App\Services;


use App\DTO\Email\SmtpConfigDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Repositories\Interfaces\EmailRepositoryInterface;

class EmailService
{
    protected EmailRepositoryInterface $emailRepository;

    public function __construct(EmailRepositoryInterface $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    /**
     * @throws GraphqlException
     */
    public function sendAccountStatusEmail(Account $account): void
    {
        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank', 'paymentSystem', 'currencies', 'groupRole'
        );

        $smtp = $this->emailRepository->getSmtpByCompanyId($account);
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubject($account);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new GraphqlException('Don\'t send email', '404');
        }
    }
}
