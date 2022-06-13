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
        if (isset($args['is_sending_mail'])) {
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
        if (isset($args['is_sending_mail'])) {
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
