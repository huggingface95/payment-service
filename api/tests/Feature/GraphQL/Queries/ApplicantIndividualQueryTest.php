<?php

namespace Tests;

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
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' =>
                'query ApplicantIndividual($id:ID!){
                    applicantIndividual(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $applicant[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'applicantIndividual' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
        ]);
    }

    public function testSetApplicantIndividualPassword(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
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
                'id' => (string) $applicant[0]->id,
                'password' => '1234567Za',
                'password_confirmation' => '1234567Za',
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

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
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' =>
                'query {
                    applicantIndividuals(orderBy: { column: ID, order: DESC }) {
                        data {
                            id
                        }
                        }
                }'
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $applicant[0]->id,
            ],
        ]);
    }

    public function testQueryApplicantIndividualWhere(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('id', 1)
            ->first();

        $this->postGraphQL([
            'query' =>
                'query ApplicantIndividuals($id: Mixed) {
                    applicantIndividuals(where: { column: ID, value: $id}) {
                        data {
                            id
                        }
                    }
                }',
            'variables' => [
                'id' => $applicant->id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $applicant->id,
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterById(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->postGraphQL([
            'query' =>
                'query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(filter: { column: ID, value: $id }) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
            'variables' => [
                'id' => (string) $applicant->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => (string) $applicant->id,
                        'first_name' => (string) $applicant->first_name,
                        'email' => (string) $applicant->email,
                        'url' => (string) $applicant->url,
                    ]]
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByEmail(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->postGraphQL([
            'query' =>
                'query TestApplicantIndividualFilters($email: Mixed) {
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => (string) $applicant->id,
                        'first_name' => (string) $applicant->first_name,
                        'email' => (string) $applicant->email,
                        'url' => (string) $applicant->url,
                    ]]
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByCompanyId(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->postGraphQL([
            'query' =>
                'query TestApplicantIndividualFilters($id: Mixed) {
                    applicantIndividuals(filter: { column: COMPANY_ID, value: $id }) {
                        data {
                            id
                            first_name
                            email
                            url
                        }
                    }
                }',
            'variables' => [
                'id' => (string) $applicant->company_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $applicant->id,
            'first_name' => (string) $applicant->first_name,
            'email' => (string) $applicant->email,
            'url' => (string) $applicant->url,
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasRiskLevel(): void
    {
        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('applicant_risk_level_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => (string) $applicant->id,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasStateReason(): void
    {
        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('applicant_state_reason_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => (string) $applicant->id,
                'first_name' => (string) $applicant->first_name,
                'email' => (string) $applicant->email,
                'url' => (string) $applicant->url,
            ];
        }

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByOwners(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->postGraphQL([
            'query' =>
                'query {
                    owners(orderBy: { column: ID, order: ASC }) {
                        id
                        first_name
                        email
                        url
                    }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $applicant->id,
            'first_name' => (string) $applicant->first_name,
            'email' => (string) $applicant->email,
            'url' => (string) $applicant->url,
        ]);
    }
}
