<?php

use Illuminate\Support\Facades\DB;

class ApplicantBankingAccessTest extends TestCase
{
    /**
     * Applicant Banking Access Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateBankingAccess()
    {
        $this->login();
        $this->graphQL('
            mutation CreateApplicantBankingAccess(
                $applicant_individual_id: ID!
                $applicant_company_id: ID!
                $member_id: ID!
            )
            {
                createApplicantBankingAccess (
                    applicant_individual_id: $applicant_individual_id
                    applicant_company_id: $applicant_company_id
                    member_id: $member_id
                    daily_limit: 100.000
                    monthly_limit: 500.000
                    operation_limit: 5.000
                    contact_administrator: false
                    can_sign_payment: false
                    can_create_payment: true
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' =>  1,
            'applicant_company_id' => 1,
            'member_id' => 2,
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

    public function testUpdateBankingAccess()
    {
        $this->login();
        $access = DB::connection('pgsql_test')->table('applicant_banking_access')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation UpdateApplicantBankingAccess(
                $id: ID!
                $applicant_individual_id: ID!
                $applicant_company_id: ID!
                $member_id: ID!
            )
            {
                updateApplicantBankingAccess (
                    id: $id
                    applicant_individual_id: $applicant_individual_id
                    applicant_company_id: $applicant_company_id
                    member_id: $member_id
                    daily_limit: 1000.000
                    monthly_limit: 5000.000
                    operation_limit: 50.000
                    contact_administrator: false
                    can_sign_payment: false
                    can_create_payment: false
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($access[0]->id),
            'applicant_individual_id' =>  1,
            'applicant_company_id' => 1,
            'member_id' => 2,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateApplicantBankingAccess' => [
                    'id' => $id['data']['updateApplicantBankingAccess']['id'],
                ],
            ],
        ]);
    }

    public function testQueryBankingAccessOrderBy()
    {
        $this->login();
        $access = DB::connection('pgsql_test')->table('applicant_banking_access')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            applicantBankingAccess(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($access[0]->id),
            ],
        ]);
    }

    public function testQueryBankingAccessWhere()
    {
        $this->login();
        $access = DB::connection('pgsql_test')->table('applicant_banking_access')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            applicantBankingAccess(where: { column: MEMBER_ID, value: 2}) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($access[0]->id),
            ],
        ]);
    }

    public function testDeleteBankingAccess()
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
