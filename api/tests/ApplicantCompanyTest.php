<?php

use Illuminate\Support\Facades\DB;

class ApplicantCompanyTest extends TestCase
{
    /**
     * ApplicantCompany Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateApplicantCompany()
    {
        $this->login();
        $seq = DB::table('applicant_companies')->max('id') + 1;
        DB::select('ALTER SEQUENCE applicant_companies_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreateApplicantCompany(
                $name: String!
                $email: EMAIL!
                $url: String!
                $phone: String!
                $country_id: ID!
                $city: String!
                $address: String!
                $expires_at: Date!
                $applicant_state_id: ID!
                $account_manager_member_id: ID!
                $company_id: ID!
                $language_id: ID!
                $owner_relation_id: ID!
                $owner_position_id: ID!
            )
            {
                createApplicantCompany (
                    name: $name
                    email: $email
                    url: $url
                    phone: $phone
                    country_id: $country_id
                    city: $city
                    address: $address
                    expires_at: $expires_at
                    applicant_state_id: $applicant_state_id
                    account_manager_member_id: $account_manager_member_id
                    company_id: $company_id
                    language_id: $language_id
                    owner_relation_id: $owner_relation_id
                    owner_position_id: $owner_position_id
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'AppCompany'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'url' => \Illuminate\Support\Str::random(6).'@com',
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
            'country_id' => 1,
            'city' => 'New York',
            'address' => '1st Street',
            'expires_at' => '1986-01-02',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'company_id' => 1,
            'language_id' => 1,
            'owner_relation_id' => 1,
            'owner_position_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantCompany()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation UpdateApplicantCompany(
                $id: ID!
                $email: EMAIL!
            )
            {
                updateApplicantCompany (
                    id: $id
                    email: $email
                )
                {
                    id
                    email
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateApplicantCompany' => [
                    'id' => $id['data']['updateApplicantCompany']['id'],
                    'email' => $id['data']['updateApplicantCompany']['email'],
                ],
            ],
        ]);
    }

    public function testQueryApplicantCompany()
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

    public function testQueryApplicantCompanyOrderBy()
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

    public function testQueryApplicantCompanyWhere()
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

    public function testQueryGetMatchedUsers()
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

    public function testDeleteApplicantCompany()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation DeleteApplicantCompany(
                $id: ID!
            )
            {
                deleteApplicantCompany (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantCompany' => [
                    'id' => $id['data']['deleteApplicantCompany']['id'],
                ],
            ],
        ]);
    }
}
