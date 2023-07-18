<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\BaseModel;
use App\Models\EmailSmtp;
use Exception;
use Illuminate\Support\Facades\Log;
use Swift_SmtpTransport;

class EmailSmtpMutator extends BaseMutator
{
    public function create($root, array $args)
    {
        $args['member_id'] = BaseModel::$memberId;
        if (isset($args['is_sending_mail']) && $args['is_sending_mail'] === true) {
            EmailSmtp::where('company_id', $args['company_id'])->update(['is_sending_mail' => false]);
        }
        if (isset($args['host_name']) && $args['host_name'] == 'mailhog') {
            $args['username'] = '';
            $args['password'] = '';
        }
        if ($this->checkSmtp($args)) {
            return EmailSmtp::create($args);
        }
    }

    public function update($root, array $args)
    {
        $emailSmtp = EmailSmtp::find($args['id']);
        if (!$emailSmtp) {
            throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
        }
        if (isset($args['is_sending_mail']) && $args['is_sending_mail'] === true) {
            EmailSmtp::where('company_id', $emailSmtp->company_id)->update(['is_sending_mail' => false]);
        }
        if (isset($args['host_name']) && $args['host_name'] == 'mailhog') {
            $args['username'] = '';
            $args['password'] = '';
        }

        if ($this->checkSmtp($args)) {
            $emailSmtp->update($args);

            return $emailSmtp;
        }
    }

    public function delete($root, array $args)
    {
        $emailSmtp = EmailSmtp::find($args['id']);
        if (!$emailSmtp) {
            throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
        }
        $emailSmtp->delete();

        return EmailSmtp::all();
    }

    public function sendEmail($root, array $args)
    {
        $emails = array_map(function ($email) {
            return trim($email);
        }, explode(',', $args['email']));

        foreach ($emails as $email) {
            if (!$this->validEmail($email)) {
                throw new GraphqlException("Email {$email} not correct", 'Bad Request', 400);
            }
        }
        if (isset($args['host_name']) && $args['host_name'] == 'mailhog') {
            $args['username'] = '';
            $args['password'] = '';
        }
        /** @var EmailSmtp $smtp */
        if (env('APP_ENV') == 'testing' || env('APP_ENV') == 'local') {
            $smtp = new EmailSmtp();
            $smtp->replay_to = (isset($args['reply_to'])) ? $args['reply_to'] : $emails;
            $smtp->security = '';
            $smtp->host_name = env('MAIL_HOST', 'mailhog');
            $smtp->username = '';
            $smtp->password = '';
            $smtp->from_email = (isset($args['from_email']) && !empty($args['from_email'])) ? $args['from_email'] : $emails;
            $smtp->from_name = (isset($args['from_name'])) ? $args['from_name'] : 'Test Name';
            $smtp->port = env('MAIL_PORT', '1025');
        } else {
            $smtp = new EmailSmtp();
            $smtp->replay_to = (isset($args['reply_to'])) ? $args['reply_to'] : $emails;
            $smtp->security = $args['security'] == 'auto' ? '' : $args['security'];
            $smtp->host_name = $args['host_name'];
            $smtp->username = $args['username'];
            $smtp->password = $args['password'];
            $smtp->from_email = (isset($args['from_email']) && !empty($args['from_email'])) ? $args['from_email'] : $emails;
            $smtp->from_name = (isset($args['from_name'])) ? $args['from_name'] : 'Test Name';
            $smtp->port = $args['port'];
        }

        $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp->from_email, '<p><strong>Success</strong></p><p><strong>SMTP works correctly</strong></p>', 'Test Email no reply');
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);

        dispatch(new SendMailJob($config, $data));

        return ['status' => 'OK', 'message' => 'Email sent for processing'];
    }

    public function checkSmtp(array $args)
    {
        try {
            if (isset($args['security'])) {
                $args['security'] == 'auto' || empty($args['security']) ? $args['security'] = null : $args['security'];
            } else {
                $args['security'] = null;
            }

            $transport = new Swift_SmtpTransport($args['host_name'], $args['port'], $args['security']);
            $transport->setUsername($args['username']);
            $transport->setPassword($args['password']);
            $mailer = new \Swift_Mailer($transport);
            $mailer->getTransport()->start();

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new GraphqlException('SMTP doesnt work correctly. Please check configuration', 'internal', 403);
        }
    }
}
