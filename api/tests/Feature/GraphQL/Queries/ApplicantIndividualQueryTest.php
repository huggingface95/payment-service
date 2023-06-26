<?php

namespace Tests;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualModules;
use Illuminate\Support\Facades\DB;

class ApplicantIndividualQueryTest extends TestCase
{
    /**
     * ApplicantIndividual Query Testing
     *
     * @return void
     */
    public function testApplicantIndividualNoAuth(): void
    {
        $this->graphQL('
             {
                applicantIndividuals
                 {
                    data {
                        id
                        fullname
                        email
                        url
                    }
                }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryApplicantIndividual(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query ApplicantIndividual($id:ID!){
                    applicantIndividual(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->prefix,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividual' => [
                    'id' => (string) $applicant[0]->prefix,
                ],
            ],
        ]);
    }

    public function testSetApplicantIndividualPassword(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation SetApplicantIndividualPassword(
                    $id: ID!
                    $password: String!
                    $password_confirmation: String!
                )
                {
                    setApplicantIndividualPassword (
                        id: $id
                        password: $password
                        password_confirmation: $password_confirmation
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->prefix,
                    'password' => '1234567Za',
                    'password_confirmation' => '1234567Za',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'setApplicantIndividualPassword' => [
                    'id' => $id['data']['setApplicantIndividualPassword']['id'],
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualOrderBy(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query {
                    applicantIndividuals(orderBy: { column: ID, order: DESC }) {
                        data {
                            id
                        }
                        }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $applicant[0]->prefix,
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByEmail(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantIndividualFilters($email: Mixed) {
                    applicantIndividuals(filter: { column: EMAIL, operator: ILIKE, value: $email }) {
                        data {
                            id
                            first_name
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
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => (string) $applicant->prefix,
                        'first_name' => (string) $applicant->first_name,
                        'email' => (string) $applicant->email,
                        'url' => (string) $applicant->url,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByFullName(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query TestApplicantIndividualFilters($fullname: Mixed) {
                    applicantIndividuals(filter: { column: FULLNAME, operator: ILIKE, value: $fullname }) {
                        data {
                            id
                            first_name
                            email
                            url
                            fullname
                        }
                    }
                }',
                'variables' => [
                    'fullname' => (string) $applicant->fullname,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => (string) $applicant->prefix,
                        'first_name' => (string) $applicant->first_name,
                        'email' => (string) $applicant->email,
                        'url' => (string) $applicant->url,
                        'fullname' => (string) $applicant->fullname,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasRiskLevel(): void
    {
        $applicants = ApplicantIndividual::query()
            ->prefixes()
            ->where('applicant_risk_level_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => (string) $applicant->prefix,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(
                        filter: { column: HAS_RISK_LEVEL_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            first_name
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
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasStateReason(): void
    {
        $applicants = ApplicantIndividual::query()
            ->prefixes()
            ->where('applicant_state_reason_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => (string) $applicant->prefix,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(
                        filter: { column: HAS_STATE_REASON_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => $applicant->applicant_state_reason_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasStatus(): void
    {
        $applicants = ApplicantIndividual::query()
            ->prefixes()
            ->where('applicant_status_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => (string) $applicant->prefix,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(
                        filter: { column: HAS_STATUS_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => $applicant->applicant_status_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByModule(): void
    {
        $module = ApplicantIndividualModules::query()
            ->first();

        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->prefixes()
            ->where('id', $module->applicant_individual_id)
            ->get();

        $data = [
            'id' => (string) $applicants[0]->prefix,
            'first_name' => (string) $applicants[0]->first_name,
            'email' => (string) $applicants[0]->email,
            'url' => (string) $applicants[0]->url,
        ];

        $this->postGraphQL(
            [
                'query' => '
                query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(
                        filter: { column: HAS_MODULES_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => $module->module_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            $data,
        ]);
    }

    public function testQueryApplicantIndividualFilterByOwners(): void
    {
        $applicant = ApplicantIndividual::query()
            ->prefixes()
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query {
                    owners(orderBy: { column: ID, order: ASC }) {
                        id
                        first_name
                        email
                        url
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $applicant->prefix,
            'first_name' => (string) $applicant->first_name,
            'email' => (string) $applicant->email,
            'url' => (string) $applicant->url,
        ]);
    }

    /**
     * @dataProvider provide_testQueryApplicantIndividualsWithFilterByCondition
     */
    public function testQueryApplicantIndividualsWithFilterByCondition($cond, $value): void
    {
        $applicants = ApplicantIndividual::query()
            ->prefixes()
            ->where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $expect = [];

        foreach ($applicants as $applicant) {
            $expect['data']['applicantIndividuals']['data'][] = [
                'id' => (string) $applicant->prefix,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query ApplicantIndividuals($id: Mixed) {
                    applicantIndividuals (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }

    public function provide_testQueryApplicantIndividualsWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['company_id', '1'],
            ['project_id', '1'],
            ['kyc_level_id', '1'],
        ];
    }
}
