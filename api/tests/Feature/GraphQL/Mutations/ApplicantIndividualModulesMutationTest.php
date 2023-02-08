<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualModulesMutationTest extends TestCase
{
    /**
     * ApplicantIndividualModules Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualModuleNoAuth(): void
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

    public function testAttachApplicantIndividualModule(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $module = DB::connection('pgsql_test')
            ->table('modules')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantIndividualModule(
                    $applicant_individual_id: ID!
                    $module_id: [ID]
                )
                {
                    createApplicantIndividualModule (
                        applicant_individual_id: $applicant_individual_id
                        module_id: $module_id
                        is_active: true
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'applicant_individual_id' => $applicant[0]->id,
                    'module_id' => $module[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantIndividualModule' => [
                    'id' => $id['data']['createApplicantIndividualModule']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantIndividualModule(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $module = DB::connection('pgsql_test')
            ->table('modules')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantIndividualModule(
                    $applicant_individual_id: ID!
                    $module_id: [ID]
                )
                {
                    deleteApplicantIndividualModule (
                        applicant_individual_id: $applicant_individual_id
                        module_id: $module_id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'applicant_individual_id' => (string) $applicant[0]->id,
                    'module_id' => (string) $module[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantIndividualModule' => [
                    'id' => $id['data']['deleteApplicantIndividualModule']['id'],
                ],
            ],
        ]);
    }
}
