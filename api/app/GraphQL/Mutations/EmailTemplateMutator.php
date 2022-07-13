<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\EmailSmtp;
use App\Models\EmailTemplate;
use App\Models\Members;
use Illuminate\Support\Facades\Auth;

class EmailTemplateMutator extends BaseMutator
{
    public function create($root, array $args)
    {
        /** @var Members $member */
        $member = Auth::user();
        $args['member_id'] = $member->id;

        return EmailTemplate::create($args);
    }

    public function sendEmailWithData($root, array $args): array
    {
        try {
            if (! $this->validEmail($args['email'])) {
                throw new GraphqlException('Email not correct', 'Bad Request', 400);
            }
            /** @var Members $member */
            $member = Auth::user();

            /** @var EmailSmtp $smtp */
            $smtp = EmailSmtp::where('member_id', $member->id)->where('company_id', $args['company_id'])->first();
            if (! $smtp) {
                throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
            }
            $smtp->replay_to = $args['email'];
            $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, $args['content'] ?? ' ', $args['subject']);
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $data));

            return ['status'=>'OK', 'message'=>'Email sent for processing'];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }
}
