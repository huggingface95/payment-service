<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\Requisite\RequisiteSendEmailDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\SendMailJob;
use App\Models\Account;
use App\Models\EmailSmtp;
use Barryvdh\DomPDF\Facade\Pdf;

class RequisiteMutator extends BaseMutator
{

    /**
     * @throws GraphqlException
     */
    public function sendEmail($root, array $args): array
    {
        if (!$this->validEmail($args['email'])) {
            throw new GraphqlException('Email not correct', 'Bad Request', 400);
        }

        try {
            $RequisiteSendEmailDTO = TransformerDTO::transform(RequisiteSendEmailDTO::class, $args);
            $account = Account::findOrFail($args['account_id']);


            /** @var EmailSmtp $smtp */
            if (!$smtp = EmailSmtp::where('member_id', $account->member->id)->where('company_id', $account->company_id)->first()) {
                throw new GraphqlException('SMTP configuration for this company not found', 'Not found', '404');
            }
            $smtp->replay_to = $args['email'];


            $data = TransformerDTO::transform(SmtpDataDTO::class, $smtp, $RequisiteSendEmailDTO->content, 'Requisite details');
            $config = TransformerDTO::transform(SmtpConfigDTO::class, $smtp);
            dispatch(new SendMailJob($config, $data));

            return ['status' => 'OK', 'message' => 'Email sent for processing'];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }

    public function download($root, array $args)
    {
        $RequisiteSendEmailDTO = TransformerDTO::transform(RequisiteSendEmailDTO::class, $args);

        $pdf = Pdf::loadHTML($RequisiteSendEmailDTO->content, 'UTF-8');

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=requisite.pdf");
        echo $pdf->output();
        exit();
    }

}
