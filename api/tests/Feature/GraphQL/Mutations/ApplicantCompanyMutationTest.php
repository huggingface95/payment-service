<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantCompanyMutationTest extends TestCase
{
    /**
     * ApplicantCompany Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantCompanyNoAuth(): void
    {
        $seq = DB::table('applicant_companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_companies_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateApplicantCompany(
                $name: String!
                $email: EMAIL!
                $company_id: ID!
                $group_id: ID!
                $project_id: ID!
            )
            {
                createApplicantCompany (
                    name: $name
                    email: $email
                    company_id: $company_id
                    group_id: $group_id
                    project_id: $project_id
                    module_ids: []
                )
                {
                    id
                }
            }
        ', [
            'name' => 'AppCompany'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'company_id' => 1,
            'group_id' => 1,
            'project_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantCompany(): void
    {
        $seq = DB::table('applicant_companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_companies_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantCompany(
                    $name: String!
                    $email: EMAIL!
                    $company_id: ID!
                    $group_id: ID!
                    $project_id: ID!
                )
                {
                    createApplicantCompany (
                        name: $name
                        email: $email
                        company_id: $company_id
                        group_id: $group_id
                        project_id: $project_id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'name' => 'AppCompany'.\Illuminate\Support\Str::random(3),
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'company_id' => 1,
                    'group_id' => 1,
                    'project_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantCompany(
                    $id: ID!
                    $name: String!
                    $email: EMAIL!
                    $company_id: ID!
                    $group_id: ID!
                    $project_id: ID!
                )
                {
                    updateApplicantCompany (
                        id: $id
                        name: $name
                        email: $email
                        company_id: $company_id
                        group_id: $group_id
                        project_id: $project_id
                        module_ids: []
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                    'name' => 'Updated name',
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'company_id' => 2,
                    'group_id' => 2,
                    'project_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantCompany' => [
                    'id' => $id['data']['updateApplicantCompany']['id'],
                    'email' => $id['data']['updateApplicantCompany']['email'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantCompanyVerificationStatus(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantCompanyVerificationStatus(
                    $id: ID!
                    $applicant_status_id: ID!
                )
                {
                    updateApplicantCompanyVerificationStatus (
                        id: $id
                        applicant_status_id: $applicant_status_id
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                    'applicant_status_id' => 3,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantCompany' => [
                    'id' => $id['data']['updateApplicantCompany']['id'],
                    'email' => $id['data']['updateApplicantCompany']['email'],
                ],
            ],
        ]);
    }

    public function testCreateApplicantIndividualCompany(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantIndividualCompany(
                    $applicant_id: ID!
                    $applicant_company_id: ID!
                    $applicant_individual_company_relation_id: ID!
                    $applicant_individual_company_position_id: ID!
                )
                {
                    createApplicantIndividualCompany (
                        applicant_id: $applicant_id
                        applicant_company_id: $applicant_company_id
                        applicant_individual_company_relation_id: $applicant_individual_company_relation_id
                        applicant_individual_company_position_id: $applicant_individual_company_position_id
                    )
                    {
                        applicant_id
                        applicant_type
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_id' => 3,
                    'applicant_company_id' => 5,
                    'applicant_individual_company_relation_id' => 1,
                    'applicant_individual_company_position_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'applicant_id' => $id['data']['createApplicantCompany']['applicant_id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividualCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual_company')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantIndividualCompany(
                    $applicant_id: ID!
                    $applicant_company_id: ID!
                    $applicant_individual_company_relation_id: ID
                    $applicant_individual_company_position_id: ID
                )
                {
                    updateApplicantIndividualCompany (
                        applicant_id: $applicant_id
                        applicant_company_id: $applicant_company_id
                        applicant_individual_company_relation_id: $applicant_individual_company_relation_id
                        applicant_individual_company_position_id: $applicant_individual_company_position_id
                    )
                    {
                        applicant_id
                        applicant_type
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_id' => (string) $applicant[0]->applicant_id,
                    'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
                    'applicant_individual_company_relation_id' => 2,
                    'applicant_individual_company_position_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'applicant_id' => $id['data']['createApplicantCompany']['applicant_id'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividualCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual_company')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantIndividualCompany(
                    $applicant_id: ID!
                    $applicant_company_id: ID!
                )
                {
                    deleteApplicantIndividualCompany (
                        applicant_id: $applicant_id
                        applicant_company_id: $applicant_company_id
                    )
                    {
                        applicant_id
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_id' => (string) $applicant[0]->applicant_id,
                    'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'applicant_id' => $id['data']['createApplicantCompany']['applicant_id'],
                ],
            ],
        ]);
    }

    public function testSendEmailVerification(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_company')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendEmailVerificationApplicantCompany(
                    $applicant_company_id: ID!
                )
                {
                    sendEmailVerificationApplicantCompany (
                        applicant_company_id: $applicant_company_id
                    )
                    {
                        id
                        name
                        email
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendEmailVerificationApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id'],
                    'name' => $id['data']['createApplicantCompany']['name'],
                    'email' => $id['data']['createApplicantCompany']['email'],
                ],
            ],
        ]);
    }

    public function testSendPhoneVerification(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_company')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SendPhoneVerificationApplicantCompany(
                    $applicant_company_id: ID!
                )
                {
                    sendPhoneVerificationApplicantCompany (
                        applicant_company_id: $applicant_company_id
                    )
                    {
                        id
                        name
                        email
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'sendEmailVerificationApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id'],
                    'name' => $id['data']['createApplicantCompany']['name'],
                    'email' => $id['data']['createApplicantCompany']['email'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantCompany(
                    $id: ID!
                )
                {
                    deleteApplicantCompany (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => strval($applicant[0]->id),
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantCompany' => [
                    'id' => $id['data']['deleteApplicantCompany']['id'],
                ],
            ],
        ]);
    }
}
