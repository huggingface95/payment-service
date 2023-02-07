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

    public function testCreateCompanyNoAuth(): void
    {
        $seq = DB::table('companies')
                ->max('id') + 1;

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
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateCompany(): void
    {
        $seq = DB::table('companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE companies_id_seq RESTART WITH '.$seq);

        $this->postGraphQL([
            'query' => '
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
            }',
            'variables' => [
                'name' =>  'Company_'.\Illuminate\Support\Str::random(5),
                'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
                'url' => 'company_'.\Illuminate\Support\Str::random(3).'.com',
                'country_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
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
                }',
            'variables' => [
                'id' => (string) $company[0]->id,
                'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
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
                }',
            'variables' => [
                'id' => (string) $company[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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
