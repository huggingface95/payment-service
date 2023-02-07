<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CompaniesQueryTest extends TestCase
{
    /**
     * Company Query Testing
     *
     * @return void
     */

    public function testCompaniesNoAuth(): void
    {
        $this->graphQL('
            {
                companies {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryCompany(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->first();

        $this->postGraphQL([
            'query' =>
                'query Company($id:ID!){
                    company(id: $id) {
                        id
                        name
                        url
                        email
                        zip
                        address
                        city
                        company_number
                        contact_name
                    }
                }',
            'variables' => [
                'id' => (string) $company->id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'company' => [
                    'id' => (string) $company->id,
                    'name' => (string) $company->name,
                    'url' => (string) $company->url,
                    'email' => (string) $company->email,
                    'zip' => (string) $company->zip,
                    'address' => (string) $company->address,
                    'city' => (string) $company->city,
                    'company_number' => (string) $company->company_number,
                    'contact_name' => (string) $company->contact_name,
                ],
            ],
        ]);
    }

    public function testCompaniesOrderBy(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query {
                    companies(orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                            name
                            url
                            email
                            zip
                            address
                            city
                            company_number
                            contact_name
                        }
                        }
                }'
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByName(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' =>
                'query Company($name: Mixed) {
                    companies(filter: { column: NAME, operator: ILIKE, value: $name }) {
                        data {
                            id
                            name
                            url
                            email
                            zip
                            address
                            city
                            company_number
                            contact_name
                        }
                    }
                }
                ',
            'variables' => [
                'name' => (string) $company[0]->name,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number ,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByEmail(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' =>
                'query Company($email: Mixed) {
                    companies(filter: { column: EMAIL, operator: ILIKE, value: $email }) {
                        data {
                            id
                            name
                            url
                            email
                            zip
                            address
                            city
                            company_number
                            contact_name
                        }
                }
            }',
            'variables' => [
                'email' => (string) $company[0]->email,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByUrl(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' =>
                'query Company($url: Mixed) {
                    companies(filter: { column: URL, operator: ILIKE, value: $url }) {
                        data {
                            id
                            name
                            url
                            email
                            zip
                            address
                            city
                            company_number
                            contact_name
                        }
                }
            }',
            'variables' => [
                'url' => (string) $company[0]->url,
            ]
        ],
            [
                "Authorization" => "Bearer " . $this->login()
            ])->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }
}
