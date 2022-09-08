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

    public function testCreateApplicantIndividualModule(): void
    {
        $this->login();

        $this->graphQL('
            mutation CreateApplicantModule(
                $name: String!
            )
            {
                createApplicantModule (
                    name: $name
                )
                {
                    id
                }
            }
        ', [
            'name' => 'Module_'.\Illuminate\Support\Str::random(7),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantModule' => [
                    'id' => $id['data']['createApplicantModule']['id'],
                ],
            ],
        ]);
    }

    public function testAttachApplicantIndividualModule(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $module = DB::connection('pgsql_test')->table('applicant_modules')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation CreateApplicantIndividualModule(
                $applicant_individual_id: ID!
                $applicant_module_id: [ID]
            )
            {
                createApplicantIndividualModule (
                    applicant_individual_id: $applicant_individual_id
                    applicant_module_id: $applicant_module_id
                    is_active: true
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);

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
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $module = DB::connection('pgsql_test')->table('applicant_modules')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation DeleteApplicantIndividualModule(
                $applicant_individual_id: ID!
                $applicant_module_id: [ID]
            )
            {
                deleteApplicantIndividualModule (
                    applicant_individual_id: $applicant_individual_id
                    applicant_module_id: $applicant_module_id
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);

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