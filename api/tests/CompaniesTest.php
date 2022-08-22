<?php

use Illuminate\Support\Facades\DB;

class CompaniesTest extends TestCase
{
    /**
     * Companies Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateCompany()
    {
        $this->login();
        $seq = DB::table('companies')->max('id') + 1;
        DB::select('ALTER SEQUENCE companies_id_seq RESTART WITH ' . $seq);
        $this->graphQL('
            mutation CreateCompany(
                $name: String!
                $email: EMAIL!
                $url: String
                $zip: String
                $address: String
                $city: String
                $company_number: String
                $country_id: ID!
            )
            {
                createCompany (
                    name: $name
                    email: $email
                    url: $url
                    zip: $zip
                    address: $address
                    city: $city
                    company_number: $company_number
                    country_id: $country_id
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'Company_'.\Illuminate\Support\Str::random(5),
            'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'url' => 'company_'.\Illuminate\Support\Str::random(3).'.com',
            'zip' => '72319',
            'address' => '1st Street',
            'city' => 'New York',
            'company_number' => '265555411'.mt_rand(1, 9999),
            'country_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createCompany' => [
                    'id' => $id['data']['createCompany']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateCompany()
    {
        $this->login();
        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation UpdateCompany(
                $id: ID!
                $email: EMAIL!
            )
            {
                updateCompany (
                    id: $id
                    email: $email
                )
                {
                    id
                    email
                }
            }
        ', [
            'id' => strval($company[0]->id),
            'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateCompany' => [
                    'id' => $id['data']['updateCompany']['id'],
                    'email' => $id['data']['updateCompany']['email'],
                ],
            ],
        ]);
    }

    public function testQueryCompany()
    {
        $this->login();
        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            query Company($id:ID!){
                company(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($company[0]->id),
        ])->seeJson([
            'data' => [
                'company' => [
                    'id' => strval($company[0]->id),
                ],
            ],
        ]);
    }

    public function testCompaniesOrderBy()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            companies(orderBy: { column: ID, order: DESC }) {
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

    public function testDeleteCompany()
    {
        $this->login();
        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation DeleteCompany(
                $id: ID!
            )
            {
                deleteCompany (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($company[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteCompany' => [
                    'id' => $id['data']['deleteCompany']['id'],
                ],
            ],
        ]);
    }
}
