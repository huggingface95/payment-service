<?php

namespace App\GraphQL\Queries\Applicant;

use App\DTO\Email\Request\EmailMemberRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Traits\CheckAccountExistForApplicant;
use App\Models\Account;
use App\Services\ApplicantService;
use App\Services\EmailService;
use App\Services\PdfService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ApplicantRequisiteQuery
{
    use  CheckAccountExistForApplicant;

    public function __construct(
        protected EmailService     $emailService,
        protected PdfService       $pdfService,
        protected ApplicantService $applicantService
    )
    {
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @throws GraphqlException
     */
    public function get($_, array $args)
    {
        $applicant = auth()->user();

        if (isset($args['account_number'])) {
            $this->checkExistsAccountByAccountNumber($args['account_number']);
        }

        if (isset($args['account_id'])) {
            $this->checkExistsAccountById($args['account_id']);
        }

        $account = Account::query()
            ->when(isset($args['account_number']), function ($q) use ($args) {
                $q->where('account_number', $args['account_number']);
            })
            ->when(isset($args['account_id']), function ($q) use ($args) {
                $q->where('id', $args['account_id']);
            })
            ->first();

        return $this->applicantService->getApplicantRequisites($applicant, $account);
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function getList($_, array $args): Collection|array
    {
        return Account::query()->where(function (Builder $q) {
            $q->whereHasMorph('clientable', [ApplicantTypeEnum::INDIVIDUAL->toString()], function (Builder $q) {
                return $q->where('client_id', Auth::user()->id);
            })->orWhere('owner_id', '=', Auth::user()->id);
        })->get();
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @throws GraphqlException
     */
    public function download($root, array $args): array
    {
        $applicant = auth()->user();

        if (isset($args['account_id'])) {
            $this->checkExistsAccountById($args['account_id']);
        }

        $account = Account::query()->where('id', $args['account_id'])->first();

        $data = $this->applicantService->getApplicantRequisites($applicant, $account);

        $rawFile = $this->pdfService->getPdfRequisites($data);

        return [
            'base64' => base64_encode($rawFile),
        ];
    }

    /**
     * @param array<string, mixed> $args
     *
     * @throws GraphqlException
     */
    public function sendEmail($root, array $args): array
    {
        $applicant = auth()->user();

        if (isset($args['account_id'])) {
            $this->checkExistsAccountById($args['account_id']);
        }
        try {
            $account = Account::query()->where('id', $args['account_id'])
                ->first();

            $args = array_merge(
                $args,
                $this->applicantService->getApplicantRequisites($applicant, $account)
            );

            $emailTemplateSubject = 'Account Requisites';
            $args['account_details_link'] = config('app.url') . "/dashboard/banking/account/details/$account->id";
            $args['customer_support'] = $account->company->backoffice_support_email;

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
