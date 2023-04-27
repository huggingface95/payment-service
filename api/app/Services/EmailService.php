<?php

namespace App\Services;

use App\DTO\Email\Request\EmailAccountMinMaxBalanceLimitRequestDTO;
use App\DTO\Email\Request\EmailApplicantCompanyRequestDTO;
use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\Email\SmtpConfigDTO;
use App\DTO\TransformerDTO;
use App\Enums\EmailExceptionCodeEnum;
use App\Exceptions\EmailException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Repositories\Interfaces\EmailRepositoryInterface;

class EmailService
{
    public function __construct(
        protected EmailRepositoryInterface $emailRepository,
        protected VerifyService $verifyService
    ) {
    }

    /**
     * @throws EmailException
     */
    public function sendAccountStatusEmail(Account $account): void
    {
        $account->load(
            'group',
            'company',
            'paymentProvider',
            'clientable',
            'owner',
            'accountState',
            'paymentBank.country',
            'paymentSystem',
            'currencies',
            'groupRole',
        );

        $smtp = $this->emailRepository->getSmtpByCompanyId($account);
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubject($account);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new EmailException('Don\'t send email', '404');
        }
    }

    /**
     * @throws EmailException
     */
    public function sendVerificationEmail(Members $member): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($member);

        $company = $member->company;
        if (! $company) {
            throw new EmailException('The member doesn\'t have a company or the company has been deleted', '404');
        }
        
        $emailTemplateName = 'Sign Up: Email Confirmation';
        $emailData = [
            'email' => $member->email,
            'name' => $member->first_name,
            'logo_company' => $company->logo_link,
            'email_confirm_url' => $company->member_verify_url.'/email/verify/'.$verifyToken->token,
            'company_name' => $company->name,
        ];

        $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $emailData, $emailTemplateName);
        $this->sendMemberEmailByMemberDto($emailDTO, true);
    }

    /**
     * @throws EmailException
     */
    public function sendChangePasswordEmail(Members $member): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($member);

        $company = $member->company;
        if (! $company) {
            throw new EmailException('The member doesn\'t have a company or the company has been deleted', '404');
        }

        $emailTemplateName = 'Reset Password';
        $emailData = [
            'email' => $member->email,
            'name' => $member->first_name,
            'logo_company' => $company->logo_link,
            'email_confirm_url' => $company->member_verify_url.'/password/change/member/'.$verifyToken->token,
            'company_name' => $company->name,
        ];

        $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $member, $emailData, $emailTemplateName);
        $this->sendMemberEmailByMemberDto($emailDTO, true);
    }

    /**
     * @throws EmailException
     */
    public function sendApplicantChangePasswordEmail(ApplicantIndividual $applicant): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($applicant);

        $company = $applicant->company;
        if (! $company) {
            throw new EmailException('The applicant doesn\'t have a company or the company has been deleted', '404');
        }

        $emailTemplateName = 'Reset Password';
        $emailData = [
            'email' => $applicant->email,
            'name' => $applicant->first_name,
            'logo_company' => $company->logo_link,
            'email_confirm_url' => $company->member_verify_url.'/password/reset/'.$verifyToken->token,
            'company_name' => $company->name,
        ];

        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $company, $emailTemplateName, $emailData);
        $this->sendApplicantEmailByApplicantDto($emailDTO);
    }

    /**
     * @throws EmailException
     */
    public function sendApplicantRegistrationLinkEmail(ApplicantIndividual $applicant): void
    {
        $verifyToken = $this->verifyService->createVerifyToken($applicant);

        $company = $applicant->company;
        if (! $company) {
            throw new EmailException('The applicant doesn\'t have a company or the company has been deleted', '404');
        }

        $emailTemplateName = 'Sign Up: Email Confirmation';
        $emailData = [
            'email' => $applicant->email,
            'client_name' => $applicant->first_name,
            'logo_company' => $company->logo_link,
            'company_name' => $company->name,
            'customer_support_url' => $company->backoffice_support_url,
            'email_confirm_url' => $company->member_verify_url.'/email/verify/'.$verifyToken->token,
        ];

        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $company, $emailTemplateName, $emailData);
        $this->sendApplicantEmailByApplicantDto($emailDTO);
    }

    /**
     * @throws EmailException
     */
    public function sendApplicantEmailByApplicantDto(EmailApplicantRequestDTO|EmailMemberRequestDTO $dto): void
    {
        $smtp = $this->emailRepository->getSmtpByCompanyId($dto->account);
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new EmailException('Don\'t send email', '404');
        }
    }

    /**
     * @throws EmailException
     */
    public function sendApplicantCompanyEmailByApplicantDto(EmailApplicantCompanyRequestDTO $dto): void
    {
        $smtp = $this->emailRepository->getSmtpByCompanyId($dto->account);
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        try {
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new EmailException('Don\'t send email', '404');
        }
    }

    /**
     * @throws EmailException
     */
    public function sendMemberEmailByMemberDto(EmailMembersRequestDTO $dto, bool $findByCompanyId = false): void
    {
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);

        try {
            $smtp = $findByCompanyId ?
                $this->emailRepository->getSmtpByCompanyId($dto->members) :
                $this->emailRepository->getSmtpByMemberId($dto->members);

            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new EmailException('SMTP NOT FOUND', EmailExceptionCodeEnum::SMTP->toString());
        }
    }

    /**
     * @throws EmailException
     */
    public function sendAccountBalanceLimitDto(EmailAccountMinMaxBalanceLimitRequestDTO $dto): void
    {
        $emailContentSubjectDto = $this->emailRepository->getTemplateContentAndSubjectByDto($dto);
        try {
            $smtp = $this->emailRepository->getSmtpByCompanyId($dto->account);
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $emailContentSubjectDto));
        } catch (\Throwable) {
            throw new EmailException('SMTP not found', '404');
        }
    }
}
