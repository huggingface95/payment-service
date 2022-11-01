<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\Requisite\RequisiteSendEmailDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Services\EmailService;
use Barryvdh\DomPDF\Facade\Pdf;

class RequisiteMutator extends BaseMutator
{
    public function __construct(
        protected EmailService $emailService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function sendEmail($root, array $args): array
    {
        try {
            $account = Account::findOrFail($args['account_id']);

            $emailTemplateSubject = 'Account Requisites';
            $args['account_details_link'] = $account->company->companySettings->client_url;
            $args['customer_support'] = $account->company->companySettings->support_email;

            $emailDTO = TransformerDTO::transform(EmailMemberRequestDTO::class, $account, $args, $emailTemplateSubject);

            $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

            return [
                'status' => 'OK',
                'message' => 'Email sent for processing',
            ];
        } catch (\Throwable $e) {
            throw new GraphqlException($e->getMessage(), 'Internal', $e->getCode());
        }
    }

    public function download($root, array $args)
    {
        $RequisiteSendEmailDTO = TransformerDTO::transform(RequisiteSendEmailDTO::class, $args);

        $pdf = Pdf::loadHTML($RequisiteSendEmailDTO->content, 'UTF-8');

        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename=requisite.pdf');
        echo $pdf->output();

        exit();
    }
}
