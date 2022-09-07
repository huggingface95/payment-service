<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantCompanyQueryTest extends TestCase
{
    /**
     * ApplicantCompany Query Testing
     *
     * @return void
     */

    public function testQueryApplicantCompany(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            query ApplicantCompany($id:ID!){
                applicantCompany(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
        ])->seeJson([
            'data' => [
                'applicantCompany' => [
                    'id' => strval($applicant[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryApplicantCompanyOrderBy(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
        query {
            applicantCompanies(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($applicant[0]->id),
            ],
        ]);
    }

    public function testQueryApplicantCompanyWhere(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'ASC')->get();

        $this->graphQL('
        query {
            applicantCompanies(where: { column: ID, value: 1}) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($applicant[0]->id),
            ],
        ]);
    }

    public function testQueryGetMatchedUsers(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'ASC')->get();

        $this->graphQL('
            query GetMatchedUsers($applicant_company_id:ID!){
                getMatchedUsers(applicant_company_id: $applicant_company_id) {
                    data {
                        applicant_individual_id
                    }
                }
            }
        ', [
            'applicant_company_id' => strval($applicant[0]->id),
        ])->seeJsonContains([
            [
                'applicant_individual_id' => strval($applicant[0]->id),
            ],
        ]);
    }
}
