<?php

namespace App\Repositories;

use App\DTO\Email\Request\EmailAccountMinMaxBalanceLimitRequestDTO;
use App\DTO\Email\Request\EmailApplicantCompanyRequestDTO;
use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\ApplicantIndividual;
use App\Models\EmailNotification;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Models\Members;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmailRepository implements EmailRepositoryInterface
{
    use ReplaceRegularExpressions;

    protected static array $staticParams = [
        'account_details_link' => '{config.app.url}/dashboard/banking/account/details/{id}',
    ];

    protected EmailSmtp $smtp;

    protected EmailTemplate $template;

    protected EmailNotification $notification;

    public function __construct(EmailSmtp $smtp, EmailTemplate $template, EmailNotification $notification)
    {
        $this->smtp = $smtp;
        $this->template = $template;
        $this->notification = $notification;
    }

    /**
     * @throws GraphqlException
     */
    public function getSmtpByCompanyId(Account|Members $account): Model|Builder
    {
        $smtp = $this->smtp->newQuery()->where('company_id', $account->company_id)->first();

        if (! $smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }

        return $smtp;
    }

    /**
     * @throws GraphqlException
     */
    public function getSmtpByMemberId(Members $members): Model|Builder
    {
        $smtp = $this->smtp->newQuery()->where('member_id', $members->id)->first();

        if (! $smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }

        return $smtp;
    }

    /**
     * @throws EmailException
     */
    public function getTemplateContentAndSubject(Account $account): SmtpDataDTO
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $this->template->newQuery()
            ->where('company_id', $account->company_id)
            ->whereRaw("lower(name) LIKE  '%".strtolower($account->accountState->name)."%'  ")
            ->first();

        if (! $emailTemplate) {
            throw new EmailException('Email template not found', '404');
        }

        /** @var EmailNotification $notification */
        $notification = $this->notification->newQuery()
            ->where('company_id', $account->company_id)
            ->whereHas('templates', function ($q) use ($emailTemplate) {
                return $q->where('email_template_id', '=', $emailTemplate->id);
            })
            ->first();

        if (! $notification) {
            throw new EmailException('Email Notification not found', '404');
        }

        $emails = ApplicantIndividual::find($account->owner_id)->first()->email;

        if (! $emails) {
            throw new EmailException('Email not found', '404');
        }

        foreach (self::$staticParams as $k => $staticParam) {
            $account->{$k} = $this->replaceStaticParams($staticParam, $account, '/\{(.*?)}/');
        }

        $content = $this->replaceObjectData($emailTemplate->getHtml(), $account, '/\{(.*?)\}/');
        $subject = $this->replaceObjectData($emailTemplate->subject, $account, '/\{(.*?)\}/');

        return TransformerDTO::transform(SmtpDataDTO::class, $emails, $content, $subject);
    }

    /**
     * @throws EmailException
     */
    public function getTemplateContentAndSubjectByDto(
        EmailApplicantRequestDTO|EmailMemberRequestDTO|EmailMembersRequestDTO|EmailApplicantCompanyRequestDTO|EmailAccountMinMaxBalanceLimitRequestDTO $dto
    ): SmtpDataDTO {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $this->template->newQuery()
            ->where('company_id', $dto->companyId)
            ->whereRaw("lower(name) LIKE  '%".strtolower($dto->emailTemplateName)."%'  ")
            ->first();

        if (! $emailTemplate) {
            throw new EmailException('Email template not found', '404');
        }

        $content = $this->replaceObjectData($emailTemplate->getHtml(), $dto->data, '/\{(.*?)\}/');
        $subject = $this->replaceObjectData($emailTemplate->subject, $dto->data, '/\{(.*?)\}/');

        return TransformerDTO::transform(SmtpDataDTO::class, $dto->email, $content, $subject);
    }
}
