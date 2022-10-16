<?php

namespace App\Services;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
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
        //TODO make it so that after Account::Create work Global Scope "AccountIndividualsCompaniesScope"
        $account =Account::find($account->id);

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

    /**
     * @throws GraphqlException
     */
    public function sendApplicantEmailByApplicantDto(EmailApplicantRequestDTO $dto): void
    {
        $smtp = $this->emailRepository->getSmtpByCompanyId($dto->account);
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new GraphqlException('Don\'t send email', '404');
        }
    }
    
}
