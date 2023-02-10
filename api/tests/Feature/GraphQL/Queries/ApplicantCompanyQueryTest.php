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
    public function testApplicantCompanyNoAuth(): void
    {
        $this->graphQL('
             {
                companies
                 {
                    data {
                        id
                        name
                        email
                        url
                    }
                }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryApplicantCompany(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query ApplicantCompany($id:ID!){
                    applicantCompany(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantCompany' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
        ]);
    }

    public function testQueryApplicantCompanyOrderBy(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    applicantCompanies(orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                        }
                        }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant[0]->id,
            ],
        ]);
    }

    public function testQueryApplicantCompanyWhere(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query applicantCompanies ($id: Mixed) {
                    applicantCompanies(where: { column: ID, value: $id}) {
                        data {
                            id
                        }
                        }
                }',
                'variables' => [
                    'id' => (string) $applicant->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
            ],
        ]);
    }

    public function testQueryGetMatchedUsers(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual_company')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query GetMatchedUsers($applicant_company_id:ID!){
                    getMatchedUsers(applicant_company_id: $applicant_company_id) {
                        applicant_id
                        applicant_type
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'applicant_id' => (string) $applicant[0]->applicant_id,
                'applicant_type' => (string) $applicant[0]->applicant_type,
                'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
            ],
        ]);
    }

    public function testQueryGetMatchedUsersFilterByApplicantType(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual_company')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query GetMatchedUsers($applicant_company_id:ID!, $applicant_type: Mixed){
                    getMatchedUsers(
                        applicant_company_id: $applicant_company_id
                        filter: {
                            column: APPLICANT_TYPE
                            value: $applicant_type
                        }
                    ) {
                        applicant_id
                        applicant_type
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
                    'applicant_type' => (string) $applicant[0]->applicant_type,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'applicant_id' => (string) $applicant[0]->applicant_id,
                'applicant_type' => (string) $applicant[0]->applicant_type,
                'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
            ],
        ]);
    }

    public function testQueryGetMatchedApplicants(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual_company')
            ->orderBy('applicant_company_id', 'ASC')
            ->where('applicant_type', 'ApplicantIndividual')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query GetMatchedApplicantndividuals($applicant_company_id:ID!){
                    getMatchedApplicantndividuals(applicant_company_id: $applicant_company_id) {
                        applicant_id
                        applicant_type
                        applicant_company_id
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'applicant_id' => (string) $applicant[0]->applicant_id,
                'applicant_type' => (string) $applicant[0]->applicant_type,
                'applicant_company_id' => (string) $applicant[0]->applicant_company_id,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterById(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: ID, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByCompanyId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($company_id: Mixed) {
                    applicantCompanies(filter: { column: COMPANY_ID, value: $company_id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'company_id' => (string) $applicant->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByAccountManagerId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: ACCOUNT_MANAGER_MEMBER_ID, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->account_manager_member_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByProjectId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->whereNotNull('project_id')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: PROJECT_ID, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->project_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByGroupRole(): void
    {
        $groupRole = DB::connection('pgsql_test')
            ->table('group_role_members_individuals')
            ->where('user_type', 'ApplicantCompany')
            ->orderBy('id', 'DESC')
            ->first();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->where('id', $groupRole->user_id)
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_GROUP_ROLE_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $groupRole->group_role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByRiskLevel(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->whereNotNull('applicant_risk_level_id')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_RISK_LEVEL_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->applicant_risk_level_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByStatus(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_STATUS_FILTER_BY_ID, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->applicant_status_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByBusinessType(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_BUSINESS_TYPE_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->applicant_company_business_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByKycLevel(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_KYC_LEVEL_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->applicant_kyc_level_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByModules(): void
    {
        $modules = DB::connection('pgsql_test')
            ->table('applicant_company_modules')
            ->orderBy('id', 'DESC')
            ->first();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->where('id', $modules->applicant_company_id)
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_MODULES_FILTER_BY_ID, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $modules->module_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByStateReasonId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_STATE_REASON_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->applicant_state_reason_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByOwnerId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($id: Mixed) {
                    applicantCompanies(filter: { column: HAS_OWNER_MIXED_ID_OR_FULLNAME, value: $id }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant->owner_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByName(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($name: Mixed) {
                    applicantCompanies(filter: { column: NAME, operator: ILIKE, value: $name }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $applicant->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByUrl(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($url: Mixed) {
                    applicantCompanies(filter: { column: URL, operator: ILIKE, value: $url }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'url' => (string) $applicant->url,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }

    public function testQueryApplicantCompanyFilterByEmail(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantCompanyFilters($email: Mixed) {
                    applicantCompanies(filter: { column: EMAIL, operator: ILIKE, value: $email }) {
                        data {
                            id
                            name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'email' => (string) $applicant->email,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant->id,
                'name' => (string) $applicant->name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ],
        ]);
    }
}
