<?php

namespace Tests\Feature\GraphQL\Queries;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RequisitesQueryTest extends TestCase
{

    public function testQueryRequisitesNoAuth(): void
    {
        $this->graphQL('
            {
                requisites {
                    id
                    account_number
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryRequisites(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query Requisite($id: ID){
                  requisite(id: $id) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'id' => (string) $requisites->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'data' => [
                'requisite' => [
                    'id' => (string) $requisites->id,
                    'account_number' => (string) $requisites->account_number,
                    'owner' => [
                        'fullname' => (string) $owner->fullname,
                        'address' => (string) $owner->address,
                        'country' => [
                           'name' => (string) $ownerCountry->name,
                        ],
                    ],
                    'bank' => [
                        'name' => (string) $bank->name,
                        'address' => (string) $bank->address,
                        'country' => [
                            'name' => (string) $bankCountry->name,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryRequisitesFilterByName(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query RequisitesFilter($account_number: Mixed){
                  requisites(filter: { column: ACCOUNT_NUMBER, operator: EQ, value: $account_number }) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'account_number' => (string) $requisites->account_number,
            ]
        ],[
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'data' => [
                'requisites' => [[
                    'id' => (string) $requisites->id,
                    'account_number' => (string) $requisites->account_number,
                    'owner' => [
                        'fullname' => (string) $owner->fullname,
                        'address' => (string) $owner->address,
                        'country' => [
                            'name' => (string) $ownerCountry->name,
                        ],
                    ],
                    'bank' => [
                        'name' => (string) $bank->name,
                        'address' => (string) $bank->address,
                        'country' => [
                            'name' => (string) $bankCountry->name,
                        ],
                    ],
                ]],
            ],
        ]);
    }

    public function testQueryRequisitesFilterByCompany(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query RequisitesFilter($id: Mixed){
                  requisites(filter: { column: COMPANY_ID, operator: EQ, value: $id }) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'id' => (string) $requisites->company_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $requisites->id,
            'account_number' => (string) $requisites->account_number,
            'owner' => [
                'fullname' => (string) $owner->fullname,
                'address' => (string) $owner->address,
                'country' => [
                    'name' => (string) $ownerCountry->name,
                ],
            ],
            'bank' => [
                'name' => (string) $bank->name,
                'address' => (string) $bank->address,
                'country' => [
                    'name' => (string) $bankCountry->name,
                ],
            ],
        ]);
    }

    public function testQueryRequisitesFilterByPaymentProvider(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query RequisitesFilter($id: Mixed){
                  requisites(filter: { column: PAYMENT_PROVIDER_ID, operator: EQ, value: $id }) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'id' => (string) $requisites->payment_provider_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $requisites->id,
            'account_number' => (string) $requisites->account_number,
            'owner' => [
                'fullname' => (string) $owner->fullname,
                'address' => (string) $owner->address,
                'country' => [
                    'name' => (string) $ownerCountry->name,
                ],
            ],
            'bank' => [
                'name' => (string) $bank->name,
                'address' => (string) $bank->address,
                'country' => [
                    'name' => (string) $bankCountry->name,
                ],
            ],
        ]);
    }

    public function testQueryRequisitesFilterByPaymentSystem(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query RequisitesFilter($id: Mixed){
                  requisites(filter: { column: PAYMENT_SYSTEM_ID, operator: EQ, value: $id }) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'id' => (string) $requisites->payment_system_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $requisites->id,
            'account_number' => (string) $requisites->account_number,
            'owner' => [
                'fullname' => (string) $owner->fullname,
                'address' => (string) $owner->address,
                'country' => [
                    'name' => (string) $ownerCountry->name,
                ],
            ],
            'bank' => [
                'name' => (string) $bank->name,
                'address' => (string) $bank->address,
                'country' => [
                    'name' => (string) $bankCountry->name,
                ],
            ],
        ]);
    }

    public function testQueryRequisitesFilterByPaymentBank(): void
    {
        $requisites = DB::connection('pgsql_test')
            ->table('accounts')
            ->first();

        $owner = DB::connection('pgsql_test')
            ->table('applicant_individual')->where('id',$requisites->owner_id)
            ->first();

        $ownerCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$owner->country_id)
            ->first();

        $bank = DB::connection('pgsql_test')
            ->table('payment_banks')->where('id',$requisites->payment_bank_id)
            ->first();

        $bankCountry = DB::connection('pgsql_test')
            ->table('countries')->where('id',$bank->country_id)
            ->first();

        $this->postGraphQL([
            'query' => '
                query RequisitesFilter($id: Mixed){
                  requisites(filter: { column: PAYMENT_BANK_ID, operator: EQ, value: $id }) {
                    id
                    owner {
                      fullname
                      address
                      country {
                        name
                      }
                    }
                    bank {
                      name
                      address
                      country {
                        name
                      }
                    }
                    account_number
                  }
               }',
            'variables' => [
                'id' => (string) $requisites->payment_bank_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $requisites->id,
            'account_number' => (string) $requisites->account_number,
            'owner' => [
                'fullname' => (string) $owner->fullname,
                'address' => (string) $owner->address,
                'country' => [
                    'name' => (string) $ownerCountry->name,
                ],
            ],
            'bank' => [
                'name' => (string) $bank->name,
                'address' => (string) $bank->address,
                'country' => [
                    'name' => (string) $bankCountry->name,
                ],
            ],
        ]);
    }
}
