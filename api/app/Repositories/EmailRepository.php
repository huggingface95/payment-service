<?php

namespace App\Repositories;

use App\DTO\Email\Request\EmailVerificationRequestDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\EmailNotification;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class EmailRepository implements EmailRepositoryInterface
{
    use ReplaceRegularExpressions;

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
    public function getSmtpByCompanyId(Account $account): Model|Builder
    {
        $smtp = $this->smtp->newQuery()->where('company_id', $account->company_id)->first();

        if (!$smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }

        return $smtp;
    }


    /**
     * @throws GraphqlException
     */
    public function getTemplateContentAndSubject(Account $account): SmtpDataDTO
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $this->template->newQuery()
            ->where('company_id', $account->company_id)
            ->whereRaw("lower(subject) LIKE  '%".strtolower($account->accountState->name)."%'  ")
            ->first();

        if (! $emailTemplate) {
            throw new GraphqlException('Email template not found', '404');
        }

        /** @var EmailNotification $notification */
        $notification = $this->notification->newQuery()
            ->where('company_id', $account->company_id)
            ->whereHas('templates', function ($q) use ($emailTemplate) {
                return $q->where('email_template_id', '=', $emailTemplate->id);
            })
            ->first();

        if (! $notification) {
            throw new GraphqlException('Email Notification not found', '404');
        }

        $emails = $notification->groupRole->users->pluck('email')->toArray();

        if (! count($emails)) {
            throw new GraphqlException('Email not found', '404');
        }

        $content = $this->replaceObjectData($emailTemplate->getHtml(), $account, '/\{(.*?)\}/');
        $subject = $this->replaceObjectData($emailTemplate->subject, $account, '/\{(.*?)\}/');

        return TransformerDTO::transform(SmtpDataDTO::class, $emails, $content, $subject);
    }

    /**
     * @throws GraphqlException
     */
    public function getTemplateContentAndSubjectByTemplateName(EmailVerificationRequestDTO $dto): SmtpDataDTO
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $this->template->newQuery()
            ->whereRaw("lower(name) LIKE  '%".strtolower($dto->emailTemplateName)."%'  ")
            ->first();

        $content = $this->replaceObjectData($emailTemplate->getHtml(), $dto->data, '/\{(.*?)\}/');
        $subject = $this->replaceObjectData($emailTemplate->subject, $dto->data, '/\{(.*?)\}/');

        return TransformerDTO::transform(SmtpDataDTO::class, $dto->email, $content, $subject);
    }
}
