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

    public function testQueryBankingAccessOrderBy(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->orderBy('id', 'DESC')
            ->get();

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

    public function testQueryBankingAccessWhere(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->orderBy('id', 'DESC')
            ->get();

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

    public function testQueryBankingAccessFilterByApplicantId(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->graphQL('
        query TestApplicantBankingAccessFilters($applicant: Mixed) {
            applicantBankingAccess(
                filter: { column: APPLICANT_INDIVIDUAL_ID, value: $applicant }
            ) {
                data {
                    id
                }
            }
        }
        ',[
            'applicant' => $access->applicant_individual_id,
            ])->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }

    public function testQueryBankingAccessFilterByApplicantCompanyId(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->graphQL('
        query TestApplicantBankingAccessFilters($applicant: Mixed) {
            applicantBankingAccess(
                filter: { column: APPLICANT_COMPANY_ID, value: $applicant }
            ) {
                data {
                    id
                }
            }
        }
        ',[
            'applicant' => $access->applicant_company_id,
        ])->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }

    public function testQueryBankingAccessFilterByMemberId(): void
    {
        $this->login();

        $access = DB::connection('pgsql_test')
            ->table('applicant_banking_access')
            ->first();

        $this->graphQL('
        query TestApplicantBankingAccessFilters($applicant: Mixed) {
            applicantBankingAccess(
                filter: { column: MEMBER_ID, value: $applicant }
            ) {
                data {
                    id
                }
            }
        }
        ',[
            'applicant' => $access->member_id,
        ])->seeJsonContains([
            [
                'id' => (string) $access->id,
            ],
        ]);
    }
}
