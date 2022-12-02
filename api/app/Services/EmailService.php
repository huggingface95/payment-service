<?php

namespace App\Services;

use App\DTO\Email\EmailRequestDTO;
use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\Email\SmtpConfigDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\Members;
use App\Repositories\Interfaces\EmailRepositoryInterface;

class EmailService
{
    public function __construct(
        protected EmailRepositoryInterface $emailRepository,
        protected VerifyService $verifyService
    )
    {
    }

    /**
     * @throws GraphqlException
     */
    public function sendAccountStatusEmail(Account $account): void
    {
        //TODO make it so that after Account::Create work Global Scope "AccountIndividualsCompaniesScope"
        $account =Account::find($account->id);

        $account->load('group', 'company', 'paymentProvider', 'clientable', 'owner',
            'accountState', 'paymentBank.country', 'paymentSystem', 'currencies', 'groupRole',
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

    public function sendVerificationEmail(Members $member): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($member);

        $company = $member->company;
        $emailTemplateName = 'Welcome! Confirm your email address';
        $emailData = [
            'email' => $member->email,
            'member_name' => $member->first_name,
            'logo_member_company' => $company->companySettings->logo_link,
            'member_email_confirm_url' => $company->companySettings->member_verify_url . '/email/verify/' . $verifyToken->token,
            'member_company_name' => $company->name,
        ];

        $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $emailData, $emailTemplateName);
        $this->sendMemberEmailByMemberDto($emailDTO, true);
    }

    public function sendChangePasswordEmail(Members $member): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($member);

        $company = $member->company;
        $emailTemplateName = '{member_company_name} has invited you to join team';
        $emailData = [
            'email' => $member->email,
            'member_name' => $member->first_name,
            'logo_member_company' => $company->companySettings->logo_link,
            'member_email_confirm_url' => $company->companySettings->member_verify_url . '/password/change/member/' . $verifyToken->token,
            'member_company_name' => $company->name,
        ];
        
        $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $emailData, $emailTemplateName);
        $this->sendMemberEmailByMemberDto($emailDTO, true);
    }

    /**
     * @throws GraphqlException
     */
    public function sendApplicantEmailByApplicantDto(EmailApplicantRequestDTO|EmailMemberRequestDTO $dto): void
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

    /**
     * @throws GraphqlException
     */
    public function sendMemberEmailByMemberDto(EmailMembersRequestDTO $dto, bool $findByCompanyId = false): void
    {
        $smtp = $findByCompanyId ?
            $this->emailRepository->getSmtpByCompanyId($dto->members) :
            $this->emailRepository->getSmtpByMemberId($dto->members);
        
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new GraphqlException('Don\'t send email', '404');
        }
    }
}
