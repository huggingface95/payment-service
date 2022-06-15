<?php

namespace App\GraphQL\Mutations;


use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\BaseModel;
use App\Models\EmailSmtp;
use App\Models\Members;

class EmailSmtpMutator
{

    public function create($root, array $args)
    {
        $args['member_id'] = BaseModel::DEFAULT_MEMBER_ID;
        if ($args['is_sending_mail'] === true ) {
            EmailSmtp::where('company_id',$args['company_id'])->update(['is_sending_mail'=>false]);
        }
        return EmailSmtp::create($args);
    }

    public function update($root, array $args)
    {
        $emailSmtp = EmailSmtp::find($args['id']);
        if (!$emailSmtp) {
            throw new GraphqlException('An entry with this id does not exist',"not found",404);
        }
        if ($args['is_sending_mail'] === true) {
            EmailSmtp::where('company_id',$emailSmtp->company_id)->update(['is_sending_mail'=>false]);
        }
        $emailSmtp->update($args);
        return $emailSmtp;
    }

    public function delete($root, array $args)
    {
        $emailSmtp = EmailSmtp::find($args['id']);
        if (!$emailSmtp) {
            throw new GraphqlException('An entry with this id does not exist',"not found",404);
        }
        $emailSmtp->delete();

        return EmailSmtp::all();
    }


    public function sendEmail($root, array $args)
    {
        /** @var EmailSmtp $smtp */
        $smtp = new EmailSmtp();
        $smtp->replay_to = (isset($args['reply_to'])) ? $args['reply_to'] : $args['email'];
        $smtp->security = $args['security'];
        $smtp->host_name = $args['host_name'];
        $smtp->username = $args['username'];
        $smtp->password = $args['password'];
        $smtp->from_email = (isset($args['from_email'])) ? $args['from_email'] : $args['email'];
        $smtp->from_name = (isset($args['from_name'])) ? $args['from_name'] : "Test Name";
        $smtp->port = $args['port'];

        $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, "Testnaaaaaa", "Subjectnaaaaa");
        $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
        dispatch(new SendMailJob($config, $data));
    }
}
