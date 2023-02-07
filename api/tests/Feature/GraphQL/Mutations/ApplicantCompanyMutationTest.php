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

    public function testCreateApplicantCompanNoAuth(): void
    {
        $seq = DB::table('applicant_companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_companies_id_seq RESTART WITH ' . $seq);

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
            'name' => 'AppCompany' . \Illuminate\Support\Str::random(3),
            'email' => 'applicant' . \Illuminate\Support\Str::random(3) . '@gmail.com',
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

        DB::select('ALTER SEQUENCE applicant_companies_id_seq RESTART WITH ' . $seq);

        $this->postGraphQL([
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
                        module_ids: []
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'name' => 'AppCompany' . \Illuminate\Support\Str::random(3),
                'email' => 'applicant' . \Illuminate\Support\Str::random(3) . '@gmail.com',
                'company_id' => 1,
                'group_id' => 1,
                'project_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

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

        $this->postGraphQL([
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
                'id' => strval($applicant[0]->id),
                'name' => 'Updated name',
                'email' => 'applicant' . \Illuminate\Support\Str::random(3) . '@gmail.com',
                'company_id' => 2,
                'group_id' => 2,
                'project_id' => 2,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

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

    public function testDeleteApplicantCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

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
