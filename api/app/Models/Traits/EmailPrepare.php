<?php

namespace App\Models\Traits;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;

trait EmailPrepare
{

    /**
     * @throws GraphqlException
     */
    public function getSmtp(Account $account): EmailSmtp
    {
        $smtp = EmailSmtp::where('member_id', $account->member_id)->where('company_id', $account->company_id)->first();

        if (!$smtp) {
            throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
        }

        try {
            $smtp->replay_to = $account->owner->email;
        } catch (\Throwable) {
            throw new GraphqlException('Проблема может быть связан с Member Access Limitation', 'Not found', '404');
        }

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
                ->where('member_id', $account->member_id)
                ->where('company_id', $account->company_id)
                ->whereRaw("lower(subject) LIKE  '%" . strtolower($account->accountState->name) . "%'  ")
                ->first();

            $content = $this->replaceObjectData($emailTemplate->getHtml(), $account, '/\{(.*?)}/');
            $subject = $this->replaceObjectData($emailTemplate->subject, $account, '/\{(.*?)}/');

            return [
                'subject' => $subject,
                'content' => $content,
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
