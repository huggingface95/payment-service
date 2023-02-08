<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualMutationTest extends TestCase
{
    /**
     * ApplicantIndividual Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualNoAuth(): void
    {
        $seq = DB::table('applicant_individual')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateApplicantIndividual(
                $first_name: String!
                $last_name: String!
                $email: EMAIL!
                $company_id: ID!
                $phone: String!
            )
            {
                createApplicantIndividual (
                    first_name: $first_name
                    last_name: $last_name
                    email: $email
                    company_id: $company_id
                    phone: $phone
                    module_ids: []
                )
                {
                    id
                }
            }
        ', [
            'first_name' =>  'Applicant_'.\Illuminate\Support\Str::random(3),
            'last_name' => 'ApplicantLast_'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'company_id' => 1,
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantIndividual(): void
    {
        $seq = DB::table('applicant_individual')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantIndividual(
                    $first_name: String!
                    $last_name: String!
                    $email: EMAIL!
                    $company_id: ID!
                    $phone: String!
                )
                {
                    createApplicantIndividual (
                        first_name: $first_name
                        last_name: $last_name
                        email: $email
                        company_id: $company_id
                        phone: $phone
                        module_ids: []
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'first_name' =>  'Applicant_'.\Illuminate\Support\Str::random(3),
                    'last_name' => 'ApplicantLast_'.\Illuminate\Support\Str::random(3),
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'company_id' => 1,
                    'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantIndividual' => [
                    'id' => $id['data']['createApplicantIndividual']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantIndividual(
                    $id: ID!
                    $first_name: String!
                    $last_name: String!
                    $email: EMAIL!
                    $phone: String!
                    $company_id: ID!
                )
                {
                    updateApplicantIndividual (
                        id: $id
                        first_name: $first_name
                        last_name: $last_name
                        email: $email
                        phone: $phone
                        company_id: $company_id
                        module_ids: []
                    )
                    {
                        id
                        email
                    }
                }',
                'variables' => [
                    'id' => strval($applicant[0]->id),
                    'first_name' => 'First test',
                    'last_name' => 'Last_name test',
                    'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'phone' => '+938276532222',
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantIndividual' => [
                    'id' => $id['data']['updateApplicantIndividual']['id'],
                    'email' => $id['data']['updateApplicantIndividual']['email'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividual(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderByDesc('id')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantIndividual(
                    $id: ID!
                )
                {
                    deleteApplicantIndividual (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantIndividual' => [
                    'id' => $id['data']['deleteApplicantIndividual']['id'],
                ],
            ],
        ]);
    }
}
