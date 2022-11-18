<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantBankingAccessMutationTest extends TestCase
{
    /**
     * Applicant Banking Access Mutation Testing
     *
     * @return void
     */

    public function testCreateBankingAccess(): void
    {
        $this->login();

        $this->graphQL('
            mutation CreateApplicantBankingAccess(
                $applicant_individual_id: ID!
                $applicant_company_id: ID!
                $role_id: ID!
            )
            {
                createApplicantBankingAccess (
                    applicant_individual_id: $applicant_individual_id
                    applicant_company_id: $applicant_company_id
                    role_id: $role_id
                    daily_limit: 100.000
                    monthly_limit: 500.000
                    operation_limit: 5.000
                    contact_administrator: false
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' =>  1,
            'applicant_company_id' => 2,
            'role_id' => 2,
        ]);
        
        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantBankingAccess' => [
                    'id' => $id['data']['createApplicantBankingAccess']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateBankingAccess(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')->table('applicant_banking_access')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation UpdateApplicantBankingAccess(
                $id: ID!
                $applicant_individual_id: ID!
                $applicant_company_id: ID!
                $role_id: ID!
            )
            {
                updateApplicantBankingAccess (
                    id: $id
                    applicant_individual_id: $applicant_individual_id
                    applicant_company_id: $applicant_company_id
                    role_id: $role_id
                    daily_limit: 100.000
                    monthly_limit: 5000.000
                    operation_limit: 50.000
                    contact_administrator: false
                )
                {
                    id
                    daily_limit
                }
            }
        ', [
            'id' => strval($access[0]->id),
            'applicant_individual_id' =>  1,
            'applicant_company_id' => 1,
            'role_id' => 3,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantBankingAccess' => [
                    'id' => $id['data']['updateApplicantBankingAccess']['id'],
                    'daily_limit' => 100
                ],
            ],
        ]);
    }

    public function testDeleteBankingAccess(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')->table('applicant_banking_access')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation DeleteApplicantBankingAccess(
                $id: ID!
            )
            {
                deleteApplicantBankingAccess (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($access[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantBankingAccess' => [
                    'id' => $id['data']['deleteApplicantBankingAccess']['id'],
                ],
            ],
        ]);
    }
}
