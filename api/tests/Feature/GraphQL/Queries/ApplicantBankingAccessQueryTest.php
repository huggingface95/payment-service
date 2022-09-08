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

    public function testQueryBankingAccessWhere(): void
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
}
