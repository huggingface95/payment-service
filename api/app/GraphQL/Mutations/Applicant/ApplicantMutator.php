<?php

namespace App\GraphQL\Mutations\Applicant;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ClientTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Companies;
use App\Models\EmailVerification;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicantMutator extends BaseMutator
{
    public function __construct(protected EmailService $emailService)
    {
    }

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function create($root, array $args)
    {
        // TODO: get client_url from request
        $args['client_url'] = 'docudots.com';

        $company = Companies::where('url', $args['client_url'])->first();
        if (!$company) {
            throw new GraphqlException('Owner company not found', 'use');
        }

        $applicantData = [
            'first_name' => $args['first_name'],
            'last_name' => $args['last_name'],
            'email' => $args['email'],
            'phone' => $args['phone'],
            'password_hash' => Hash::make($args['password']),
            'password_salt' => Hash::make($args['password']),
            'is_verification_email' => false,
            'is_active' => false,
            'country_id' => 1,
            'company_id' => $company->id,
        ];

        $applicant = ApplicantIndividual::create($applicantData);

        if (isset($args['client_type']) && $args['client_type'] === 'Corporate') {
            $applicantCompany = ApplicantCompany::create([
                'name' => $args['company_name'],
                'email' => $applicant->email,
                'url' => $args['url'],
                'phone' => $applicant->phone,
                'country_id' => $applicant->country_id,
                'owner_id' => $applicant->id,
                'company_id' => $company->id,
            ]);

            $applicant->companies()->attach($applicantCompany->id, [
                'applicant_individual_company_relation_id' => 1,
                'applicant_individual_company_position_id' => 1,
            ]);
        }

        $verifyToken = EmailVerification::create([
            'client_id' => $applicant->id,
            'type' => ClientTypeEnum::APPLICANT->toString(),
            'token' => Str::random(64),
        ]);

        $emailTemplateName = 'Welcome! Confirm your email address';
        $emailData = [
            'client_name' => $applicant->first_name,
            'email_confirm_url' => $company->companySettings->client_url . '/email/verify/' . $verifyToken->token,
            'member_company_name' => $company->name,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $company, $emailTemplateName, $emailData);

        $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

        // TODO: remove this line
        $applicant->email_confirm_url = 'https://dev.account.docudots.com/email/verify/' . $verifyToken->token;

        return $applicant;
    }

}
