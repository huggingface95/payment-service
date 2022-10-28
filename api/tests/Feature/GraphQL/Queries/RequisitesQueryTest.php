<?php

namespace Tests\Feature\GraphQL\Queries;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RequisitesQueryTest extends TestCase
{

    public function testQueryRequisites(): void
    {
        $this->login();

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

        $this->graphQL('
            query Requisite($account_number: String){
              requisite(account_number: $account_number) {
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
           }
        ', [
            'account_number' => (string) $requisites->account_number,
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
        $this->login();

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

        $this->graphQL('
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
           }
        ', [
            'account_number' => (string) $requisites->account_number,
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
        $this->login();

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

        $this->graphQL('
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
           }
        ', [
            'id' => (string) $requisites->company_id,
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
        $this->login();

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

        $this->graphQL('
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
           }
        ', [
            'id' => (string) $requisites->payment_provider_id,
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
        $this->login();

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

        $this->graphQL('
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
           }
        ', [
            'id' => (string) $requisites->payment_system_id,
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
        $this->login();

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

        $this->graphQL('
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
           }
        ', [
            'id' => (string) $requisites->payment_bank_id,
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
