<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantCompanyModulesTest extends TestCase
{
    /**
     * ApplicantCompanyModules Testing
     *
     * @return void
     */
    public function testCreateApplicantCompanyModule()
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

    public function testAttachApplicantCompanyModule()
    {
        $this->login();
        $applicant_company = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $module = DB::connection('pgsql_test')->table('applicant_modules')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation CreateApplicantCompanyModule(
                $applicant_company_id: ID!
                $applicant_module_id: [ID]
            )
            {
                createApplicantCompanyModule (
                    applicant_company_id: $applicant_company_id
                    applicant_module_id: $applicant_module_id
                    is_active: true
                )
                {
                    id
                }
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_module_id' => strval($module[0]->id),
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

    public function testDetachApplicantCompanyModule()
    {
        $this->login();
        $applicant_company = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $module = DB::connection('pgsql_test')->table('applicant_modules')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation DeleteApplicantCompanyModule(
                $applicant_company_id: ID!
                $applicant_module_id: [ID]
            )
            {
                deleteApplicantCompanyModule (
                    applicant_company_id: $applicant_company_id
                    applicant_module_id: $applicant_module_id
                )
                {
                    id
                }
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_module_id' => strval($module[0]->id),
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
