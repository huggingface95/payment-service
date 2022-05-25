<?php

namespace App\GraphQL\Mutations;


use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Jobs\SendMailJob;
use App\Models\BaseModel;
use App\Models\EmailSmtp;
use App\Models\Members;

class EmailSmtpMutator
{

    public function create($root, array $args)
    {
        $args['member_id'] = BaseModel::DEFAULT_MEMBER_ID;

        return EmailSmtp::create($args);
    }

    public function sendEmail($root, array $args)
    {
        /** @var Members $member */
        $member = Members::find(BaseModel::DEFAULT_MEMBER_ID);
        /** @var EmailSmtp $smtp */
        $smtp = EmailSmtp::where('member_id', $member->id)->first();
        $smtp->replay_to = $args['email'];
        $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, "Testnaaaaaa", "Subjectnaaaaa");
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
        dispatch(new SendMailJob($config, $data));
    }
}
