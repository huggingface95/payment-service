<?php

namespace App\Models\Traits;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\EmailNotification;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;

trait EmailPrepare
{

    /**
     * @throws GraphqlException
     */
    public function getSmtp(Account $account, array $emails): EmailSmtp
    {
        $smtp = EmailSmtp::where('company_id', $account->company_id)->first();

        if (!$smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }
        $smtp->replay_to = $emails;

        return $smtp;
    }

    /**
     * @throws GraphqlException
     */
    public function getTemplateContentAndSubject(Account $account): array
    {
        try {
            /** @var EmailTemplate $emailTemplate */
            $emailTemplate = EmailTemplate::query()
                ->where('company_id', $account->company_id)
                ->whereRaw("lower(subject) LIKE  '%" . strtolower($account->accountState->name) . "%'  ")
                ->first();

            if (!$emailTemplate){
                throw new GraphqlException('Email template not found', '404');
            }

            $notification = EmailNotification::query()
                ->where('company_id', $account->company_id)
                ->whereHas('templates', function($q) use ($emailTemplate){
                    return $q->where('email_template_id', '=', $emailTemplate->id);
                })
                ->first();

            if (!$notification){
                throw new GraphqlException('Email Notification not found', '404');
            }

            $emails = $notification->groupRole->users->pluck('email')->toArray();

            $content = $this->replaceObjectData($emailTemplate->getHtml(), $account, '/\{(.*?)}/');
            $subject = $this->replaceObjectData($emailTemplate->subject, $account, '/\{(.*?)}/');

            return [
                'subject' => $subject,
                'content' => $content,
                'emails' => $emails
            ];
        } catch (\Throwable) {
            throw new GraphqlException('Email template error', '404');
        }
    }

    /**
     * @throws GraphqlException
     */
    public function sendEmail(EmailSmtp $smtp, array $data): void
    {
        try {
            $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, $data['content'], $data['subject']);
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $data));
        } catch (\Throwable) {
            throw new GraphqlException('Don\'t send email', '404');
        }
    }

}
