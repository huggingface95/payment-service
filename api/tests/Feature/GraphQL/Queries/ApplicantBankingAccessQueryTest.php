<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantBankingAccessQueryTest extends TestCase
{
    /**
     * Applicant Banking Access Query Testing
     *
     * @return void
     */
    public function testApplicantBankingAccessNoAuth(): void
    {
        $this->graphQL(
            '
             query ApplicantBankingAccesses($applicant_company_id: ID!) {
                applicantBankingAccesses (
                    applicant_company_id: $applicant_company_id
                ) {
                    data {
                        id
                    }
                }
             }',
            [
                'applicant_company_id' => 1,
            ]
        )->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryBankingAccessOrderBy(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query ApplicantBankingAccesses($applicant_company_id: ID!) {
                        applicantBankingAccesses( applicant_company_id: $applicant_company_id, orderBy: { column: ID, order: ASC }) {
                            data {
                                id
                            }
                        }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $access[0]->applicant_company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $access[0]->id,
            ],
        ]);
    }

    public function testQueryBankingAccessFilterByMemberId(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->where('member_id', 2)
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query ApplicantBankingAccesses($applicant_company_id: ID!, $member_id: Mixed) {
                        applicantBankingAccesses( applicant_company_id: $applicant_company_id, filter: { column: MEMBER_ID, value: $member_id }) {
                            data {
                                id
                            }
                        }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $access->applicant_company_id,
                    'member_id' => (string) $access->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }

    public function testQueryBankingAccess(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query ApplicantBankingAccess($id: ID) {
                        applicantBankingAccess(id: $id) {
                                id
                        }
                }',
                'variables' => [
                    'id' => (string) $access->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }

    public function testQueryGrantedBankingAccess(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query GrantedBankingAccess ($applicant_individual_id: ID!, $applicant_company_id: ID!) {
                        grantedBankingAccess (
                            applicant_individual_id: $applicant_individual_id
                            applicant_company_id: $applicant_company_id
                        ) {
                        data
                            {
                                id
                            }
                        }
                }',
                'variables' => [
                    'applicant_individual_id' => (string) $access->applicant_individual_id,
                    'applicant_company_id' => (string) $access->applicant_company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }

    public function testQueryGrantedBankingAccessByMemberId(): void
    {
        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query GrantedBankingAccess ($applicant_individual_id: ID!, $applicant_company_id: ID!, $member_id: Mixed) {
                        grantedBankingAccess (
                            applicant_individual_id: $applicant_individual_id
                            applicant_company_id: $applicant_company_id
                            filter: {column: MEMBER_ID, value: $member_id}
                        ) {
                        data
                            {
                                id
                            }
                        }
                }',
                'variables' => [
                    'applicant_individual_id' => (string) $access->applicant_individual_id,
                    'applicant_company_id' => (string) $access->applicant_company_id,
                    'member_id' => (string) $access->member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }
}
