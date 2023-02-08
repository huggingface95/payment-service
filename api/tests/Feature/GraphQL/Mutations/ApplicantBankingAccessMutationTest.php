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
    public function testCreateBankingAccessNoAuth(): void
    {
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
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateBankingAccess(): void
    {
        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'applicant_individual_id' =>  1,
                    'applicant_company_id' => 2,
                    'role_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantBankingAccess(
                    $id: ID!
                    $applicant_individual_id: ID!
                    $applicant_company_id: ID!
                    $role_id: ID!
                    $dl: Decimal!
                    $ml: Decimal!
                    $ol: Decimal!
                )
                {
                    updateApplicantBankingAccess (
                        id: $id
                        applicant_individual_id: $applicant_individual_id
                        applicant_company_id: $applicant_company_id
                        role_id: $role_id
                        daily_limit: $dl
                        monthly_limit: $ml
                        operation_limit: $ol
                        contact_administrator: false
                    )
                    {
                        id
                        daily_limit
                        monthly_limit
                        operation_limit
                    }
                }',
                'variables' => [
                    'id' => (string) $access[0]->id,
                    'applicant_individual_id' =>  1,
                    'applicant_company_id' => 1,
                    'role_id' => 3,
                    'dl' => 1000.00,
                    'ml' => 5000.00,
                    'ol' => 50.00,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantBankingAccess' => [
                    'id' => $id['data']['updateApplicantBankingAccess']['id'],
                    'daily_limit' => $id['data']['updateApplicantBankingAccess']['daily_limit'],
                    'monthly_limit' => $id['data']['updateApplicantBankingAccess']['monthly_limit'],
                    'operation_limit' => $id['data']['updateApplicantBankingAccess']['operation_limit'],
                ],
            ],
        ]);
    }

    public function testDeleteBankingAccess(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => strval($access[0]->id),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
