<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CompaniesMutationTest extends TestCase
{
    /**
     * Companies Mutation Testing
     *
     * @return void
     */

    public function testCreateCompany(): void
    {
        $this->login();

        $seq = DB::table('companies')->max('id') + 1;
        DB::select('ALTER SEQUENCE companies_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateCompany(
                $name: String!
                $email: EMAIL!
                $url: String!
                $zip: String
                $address: String
                $city: String
                $company_number: String
                $country_id: ID!
                $contact_name: String
            ) {
            createCompany(
                name: $name
                email: $email
                url: $url
                zip: $zip
                address: $address
                city: $city
                company_number: $company_number
                country_id: $country_id
                contact_name: $contact_name
            ) {
                id
                name
                url
                email
                zip
                address
                city
                country {
                    id
                    name
                }
                company_number
                contact_name
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
            'contact_name' => 'Petrov Vasiliy',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createCompany' => [
                    'id' => $id['data']['createCompany']['id'],
                    'name' => $id['data']['createCompany']['name'],
                    'url' => $id['data']['createCompany']['url'],
                    'email' => $id['data']['createCompany']['email'],
                    'zip' => $id['data']['createCompany']['zip'],
                    'address' => $id['data']['createCompany']['address'],
                    'city' => $id['data']['createCompany']['city'],
                    'company_number' => $id['data']['createCompany']['company_number'],
                    'country' => $id['data']['createCompany']['country'],
                    'contact_name' => $id['data']['createCompany']['contact_name'],
                ],
            ],
        ]);
    }

    public function testUpdateCompany(): void
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
                    name
                    url
                    email
                    zip
                    address
                    city
                    country {
                        id
                        name
                    }
                    company_number
                    contact_name
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
                    'name' => $id['data']['updateCompany']['name'],
                    'url' => $id['data']['updateCompany']['url'],
                    'email' => $id['data']['updateCompany']['email'],
                    'zip' => $id['data']['updateCompany']['zip'],
                    'address' => $id['data']['updateCompany']['address'],
                    'city' => $id['data']['updateCompany']['city'],
                    'company_number' => $id['data']['updateCompany']['company_number'],
                    'country' => $id['data']['updateCompany']['country'],
                    'contact_name' => $id['data']['updateCompany']['contact_name'],
                ],
            ],
        ]);
    }

    public function testDeleteCompany(): void
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
