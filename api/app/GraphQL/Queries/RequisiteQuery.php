<?php

namespace App\GraphQL\Queries;

use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Services\ApplicantService;
use App\Services\EmailService;
use App\Services\PdfService;

class RequisiteQuery
{
    public function __construct(
        protected EmailService $emailService,
        protected PdfService $pdfService,
        protected ApplicantService $applicantService
    ) {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function download($root, array $args)
    {
        $account = Account::where('id', $args['account_id'])->first();
        $applicant = $account->owner;

        $data = $this->applicantService->getApplicantRequisites($applicant, $account);

        $rawFile = $this->pdfService->getPdfRequisites($data);

        return [
            'base64' => base64_encode($rawFile),
        ];
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     * @throws GraphqlException
     */
    public function sendEmail($root, array $args)
    {
        try {
            $account = Account::where('id', $args['account_id'])->firstOrFail();
            $applicant = $account->owner;

            $args = array_merge(
                $args,
                $this->applicantService->getApplicantRequisites($applicant, $account)
            );

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
}
