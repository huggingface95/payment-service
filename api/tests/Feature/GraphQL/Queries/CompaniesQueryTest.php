<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CompaniesQueryTestiesTest extends TestCase
{
    /**
     * Companies Query Testing
     *
     * @return void
     */

    public function testQueryCompany(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')->table('companies')->first();

        $this->graphQL('
            query Company($id:ID!){
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
            }
        ', [
            'id' => strval($company->id),
        ])->seeJson([
            'data' => [
                'company' => [
                    'id' => strval($company->id),
                    'name' => strval($company->name),
                    'url' => strval($company->url),
                    'email' => strval($company->email),
                    'zip' => strval($company->zip),
                    'address' => strval($company->address),
                    'city' => strval($company->city),
                    'company_number' => strval($company->company_number),
                    'contact_name' => strval($company->contact_name),
                ],
            ],
        ]);
    }

    public function testCompaniesOrderBy(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
        query {
            companies(orderBy: { column: ID, order: DESC }) {
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
        }')->seeJsonContains([
            [
                'id' => strval($company[0]->id),
                'name' => strval($company[0]->name),
                'url' => strval($company[0]->url),
                'email' => strval($company[0]->email),
                'zip' => strval($company[0]->zip),
                'address' => strval($company[0]->address),
                'city' => strval($company[0]->city),
                'company_number' => strval($company[0]->company_number),
                'contact_name' => strval($company[0]->contact_name),
            ],
        ]);
    }

    public function testCompaniesFilterByName(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
        query Companies($name: Mixed) {
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
        ', [
            'name' => strval($company[0]->name),
        ])->seeJsonContains([
            [
                'id' => strval($company[0]->id),
                'name' => strval($company[0]->name),
                'url' => strval($company[0]->url),
                'email' => strval($company[0]->email),
                'zip' => strval($company[0]->zip),
                'address' => strval($company[0]->address),
                'city' => strval($company[0]->city),
                'company_number' => strval($company[0]->company_number),
                'contact_name' => strval($company[0]->contact_name),
            ],
        ]);
    }

    public function testCompaniesFilterByEmail(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            query Companies($email: Mixed) {
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
            }
        ', [
            'email' => strval($company[0]->email),
        ])->seeJsonContains([
            [
                'id' => strval($company[0]->id),
                'name' => strval($company[0]->name),
                'url' => strval($company[0]->url),
                'email' => strval($company[0]->email),
                'zip' => strval($company[0]->zip),
                'address' => strval($company[0]->address),
                'city' => strval($company[0]->city),
                'company_number' => strval($company[0]->company_number),
                'contact_name' => strval($company[0]->contact_name),
            ],
        ]);
    }

    public function testCompaniesFilterByUrl(): void
    {
        $this->login();

        $company = DB::connection('pgsql_test')->table('companies')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            query Companies($url: Mixed) {
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
            }
        ', [
            'url' => strval($company[0]->url),
        ])->seeJsonContains([
            [
                'id' => strval($company[0]->id),
                'name' => strval($company[0]->name),
                'url' => strval($company[0]->url),
                'email' => strval($company[0]->email),
                'zip' => strval($company[0]->zip),
                'address' => strval($company[0]->address),
                'city' => strval($company[0]->city),
                'company_number' => strval($company[0]->company_number),
                'contact_name' => strval($company[0]->contact_name),
            ],
        ]);
    }
}
