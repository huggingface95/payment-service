<?php

namespace App\GraphQL\Queries\Applicant;

use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Services\ApplicantService;
use App\Services\EmailService;
use App\Services\PdfService;

class ApplicantRequisiteQuery
{
    public function __construct(
        protected EmailService $emailService,
        protected PdfService $pdfService,
        protected ApplicantService $applicantService
    ) {
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @throws GraphqlException
     */
    public function get($_, array $args)
    {
        $applicant = auth()->user();

        $account = Account::where('account_number', $args['account_number'])
            ->where('owner_id', $applicant->id)
            ->first();

        if (! $account) {
            throw new GraphqlException('Not found Account', 'not found', 404);
        }

        $requsites = $this->applicantService->getApplicantRequisites($applicant, $account);

        return $requsites;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args)
    {
        $applicant = auth()->user();

        $accounts = Account::where('owner_id', $applicant->id)->get();

        return $accounts;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function download($root, array $args)
    {
        $applicant = auth()->user();

        $account = Account::where('id', $args['account_id'])
            ->where('owner_id', $applicant->id)
            ->first();

        $data = $this->applicantService->getApplicantRequisites($applicant, $account);

        $rawFile = $this->pdfService->getPdfRequisites($data);

        return [
            'base64' => base64_encode($rawFile),
        ];
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     *
     * @throws GraphqlException
     */
    public function sendEmail($root, array $args)
    {
        $applicant = auth()->user();

        try {
            $account = Account::where('id', $args['account_id'])
                ->where('owner_id', $applicant->id)
                ->firstOrFail();

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
