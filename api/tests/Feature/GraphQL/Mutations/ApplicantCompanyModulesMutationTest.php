<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantCompanyModulesMutationTest extends TestCase
{
    /**
     * ApplicantCompanyModules Mutation Testing
     *
     * @return void
     */

    public function testCreateApplicantCompanyModuleNoAuth(): void
    {
        $this->graphQL('
            mutation CreateModule(
                $name: String!
            )
            {
                createModule (
                    name: $name
                )
                {
                    id
                }
            }
        ', [
            'name' => 'Module_'.\Illuminate\Support\Str::random(7),
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testAttachApplicantCompanyModule(): void
    {
        $applicant_company = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $module = DB::connection('pgsql_test')
            ->table('modules')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation CreateApplicantCompanyModule(
                    $applicant_company_id: ID!
                    $module_id: [ID]
                )
                {
                    createApplicantCompanyModule (
                        applicant_company_id: $applicant_company_id
                        module_id: $module_id
                        is_active: true
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'applicant_company_id' => (string) $applicant_company[0]->id,
                'module_id' => (string) $module[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompanyModule' => [
                    'id' => $id['data']['createApplicantCompanyModule']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantCompanyModule(): void
    {
        $applicant_company = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $module = DB::connection('pgsql_test')
            ->table('modules')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DeleteApplicantCompanyModule(
                    $applicant_company_id: ID!
                    $module_id: [ID]
                )
                {
                    deleteApplicantCompanyModule (
                        applicant_company_id: $applicant_company_id
                        module_id: $module_id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'applicant_company_id' => (string) $applicant_company[0]->id,
                'module_id' => (string) $module[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantCompanyModule' => [
                    'id' => $id['data']['deleteApplicantCompanyModule']['id'],
                ],
            ],
        ]);
    }
}
