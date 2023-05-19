<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualMutationTest extends TestCase
{
    /**
     * ApplicantIndividual Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualNoAuth(): void
    {
        $seq = DB::table('applicant_individual')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateApplicantIndividual(
                $first_name: String!
                $last_name: String!
                $email: EMAIL!
                $company_id: ID!
                $phone: String!
            )
            {
                createApplicantIndividual (
                    first_name: $first_name
                    last_name: $last_name
                    email: $email
                    company_id: $company_id
                    phone: $phone
                    module_ids: []
                )
                {
                    id
                }
            }
        ', [
            'first_name' =>  'Applicant_'.\Illuminate\Support\Str::random(3),
            'last_name' => 'ApplicantLast_'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'company_id' => 1,
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantIndividual(): void
    {
        $seq = DB::table('applicant_individual')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantIndividual(
                    $first_name: String!
                    $last_name: String!
                    $email: EMAIL!
                    $company_id: ID!
                    $phone: String!
                )
                {
                    createApplicantIndividual (
                        first_name: $first_name
                        last_name: $last_name
                        email: $email
                        company_id: $company_id
                        phone: $phone
                        module_ids: []
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'first_name' =>  'Applicant_'.\Illuminate\Support\Str::random(3),
                    'last_name' => 'ApplicantLast_'.\Illuminate\Support\Str::random(3),
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'company_id' => 1,
                    'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantIndividual' => [
                    'id' => $id['data']['createApplicantIndividual']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantIndividual(
                    $id: ID!
                    $first_name: String!
                    $last_name: String!
                    $email: EMAIL!
                    $phone: String!
                )
                {
                    updateApplicantIndividual (
                        id: $id
                        first_name: $first_name
                        last_name: $last_name
                        email: $email
                        phone: $phone
                        module_ids: []
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                    'first_name' => 'First test',
                    'last_name' => 'Last_name test',
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'phone' => '+938276532222',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantIndividual' => [
                    'id' => $id['data']['updateApplicantIndividual']['id'],
                    'email' => $id['data']['updateApplicantIndividual']['email'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividualVerificationStatus(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantIndividualVerificationStatus(
                    $id: ID!
                    $status_id: ID!
                )
                {
                    updateApplicantIndividualVerificationStatus (
                        id: $id
                        applicant_status_id: $status_id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                    'status_id' => 3,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantIndividualVerificationStatus' => [
                    'id' => $id['data']['updateApplicantIndividualVerificationStatus']['id'],
                    'email' => $id['data']['updateApplicantIndividualVerificationStatus']['email'],
                ],
            ],
        ]);
    }

    public function testSetApplicantIndividualSecurityPin(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation setApplicantSecurityPin(
                    $id: ID!
                )
                {
                    setApplicantSecurityPin (
                        id: $id
                    )
                    {
                        id
                        email
                        security_pin
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'setApplicantSecurityPin' => [
                    'id' => $id['data']['setApplicantSecurityPin']['id'],
                    'email' => $id['data']['setApplicantSecurityPin']['email'],
                    'security_pin' => $id['data']['setApplicantSecurityPin']['security_pin'],
                ],
            ],
        ]);
    }

    public function testSendEmailVerificationApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendEmailVerification(
                    $id: ID!
                )
                {
                    sendEmailVerification (
                        applicant_id: $id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendEmailVerification' => [
                    'id' => $id['data']['sendEmailVerification']['id'],
                    'email' => $id['data']['sendEmailVerification']['email'],
                ],
            ],
        ]);
    }

    public function testSendPhoneVerificationApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendPhoneVerification(
                    $id: ID!
                )
                {
                    sendPhoneVerification (
                        applicant_id: $id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendPhoneVerification' => [
                    'id' => $id['data']['sendPhoneVerification']['id'],
                    'email' => $id['data']['sendPhoneVerification']['email'],
                ],
            ],
        ]);
    }

    public function testSendEmailResetPasswordApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendEmailResetPassword(
                    $id: ID!
                )
                {
                    sendEmailResetPassword (
                        applicant_id: $id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendEmailResetPassword' => [
                    'id' => $id['data']['sendEmailResetPassword']['id'],
                    'email' => $id['data']['sendEmailResetPassword']['email'],
                ],
            ],
        ]);
    }

    public function testSendEmailRegistrationLinkApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendEmailRegistation(
                    $id: ID!
                )
                {
                    sendEmailRegistation (
                        applicant_id: $id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendEmailRegistation' => [
                    'id' => $id['data']['sendEmailRegistation']['id'],
                    'email' => $id['data']['sendEmailRegistation']['email'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderByDesc('id')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantIndividual(
                    $id: ID!
                )
                {
                    deleteApplicantIndividual (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantIndividual' => [
                    'id' => $id['data']['deleteApplicantIndividual']['id'],
                ],
            ],
        ]);
    }
}
