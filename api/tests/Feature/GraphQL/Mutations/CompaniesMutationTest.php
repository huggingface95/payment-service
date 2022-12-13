<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CompaniesMutationTest extends TestCase
{
    /**
     * Company Mutation Testing
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
                $country_id: ID!
            ) {
            createCompany(
                name: $name
                email: $email
                url: $url
                country_id: $country_id
            ) {
                id
                name
                url
                email
                country {
                    id
                    name
                }
            }
        }
        ', [
            'name' =>  'Company_'.\Illuminate\Support\Str::random(5),
            'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'url' => 'company_'.\Illuminate\Support\Str::random(3).'.com',
            'country_id' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createCompany' => [
                    'id' => $id['data']['createCompany']['id'],
                    'name' => $id['data']['createCompany']['name'],
                    'url' => $id['data']['createCompany']['url'],
                    'email' => $id['data']['createCompany']['email'],
                    'country' => $id['data']['createCompany']['country'],
                ],
            ],
        ]);
    }

    public function testUpdateCompany(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

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

        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

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
