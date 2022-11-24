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

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

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

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

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

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'ASC')
            ->get();

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

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
            query GetMatchedUsers($applicant_company_id:ID!){
                getMatchedUsers(applicant_company_id: $applicant_company_id) {
                    applicant_id
                }
            }
        ', [
            'applicant_company_id' => strval($applicant[0]->id),
        ])->seeJsonContains([
            [
                'applicant_id' => strval($applicant[0]->id),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterById(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($id: Mixed) {
                applicantCompanies(filter: { column: ID, value: $id }) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->id),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByCompanyId(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($company_id: Mixed) {
                applicantCompanies(filter: { column: COMPANY_ID, value: $company_id }) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'company_id' => strval($applicant->company_id),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByAccountManagerId(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($id: Mixed) {
                applicantCompanies(
                    filter: { column: ACCOUNT_MANAGER_MEMBER_ID, value: $id }
                ) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->account_manager_member_id),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByStateReasonId(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($id: Mixed) {
                applicantCompanies(
                    filter: { column: HAS_STATE_REASON_MIXED_ID_OR_NAME, value: $id }
                ) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->applicant_state_reason_id),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByOwnerId(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($id: Mixed) {
                applicantCompanies(
                    filter: { column: HAS_OWNER_MIXED_ID_OR_FULLNAME, value: $id }
                ) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->owner_id),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByName(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($name: Mixed) {
                applicantCompanies(filter: { column: NAME, operator: ILIKE, value: $name }) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'name' => strval($applicant->name),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByUrl(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($url: Mixed) {
                applicantCompanies(filter: { column: URL, operator: ILIKE, value: $url }) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'url' => strval($applicant->url),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByEmail(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->graphQL('
            query TestApplicantCompanyFilters($email: Mixed) {
                applicantCompanies(filter: { column: EMAIL, operator: ILIKE, value: $email }) {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
            }
        ', [
            'email' => strval($applicant->email),
        ])->seeJsonContains([
            [
                'id' => strval($applicant->id),
                'name' => strval($applicant->name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ],
        ]);
    }
}
