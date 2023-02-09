<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\Account;
use App\Models\Country;
use App\Models\FeeType;
use App\Models\PriceListFee;
use App\Models\RespondentFee;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentsMutationTest extends TestCase
{
    private $data = [
        'account_id' => 1,
    ];

    private $amount = [];

    public function testCreatePaymentNoAuth(): void
    {
        $faker = Faker::create();

        $data = [
            'account_id' => $this->data['account_id'],
            'currency_id' => 1,
            'price_list_fees_id' => 1,
            'recipient_account' => $faker->name(),
            'recipient_bank_name' => $faker->name(),
            'recipient_bank_address' => $faker->address(),
            'recipient_bank_swift' => $faker->swiftBicNumber(),
            'recipient_bank_country_id' => 1,
            'beneficiary_name' => $faker->name(),
            'beneficiary_state' => $faker->name(),
            'beneficiary_country_id' => 3,
            'beneficiary_address' => $faker->address(),
            'beneficiary_city' => $faker->city(),
            'beneficiary_zip' => '122112',
            'amount' => 1000,
            'fee_type_id' => 1,
            'urgency_id' => 1,
            'operation_type_id' => 1,
            'payment_provider_id' => 1,
            'respondent_fees_id' => 3,
            'company_id' => 1,
            // 'execution_at' => DateTime
        ];

        $this->graphQL('
        mutation(
            $account_id: ID!
            $currency_id: ID!
            $price_list_fees_id: ID!
            $recipient_account: String!
            $recipient_bank_name: String!
            $recipient_bank_address: String!
            $recipient_bank_swift: String!
            $recipient_bank_country_id: ID!
            $beneficiary_name: String!
            $beneficiary_state: String!
            $beneficiary_country_id: ID!
            $beneficiary_address: String!
            $beneficiary_city: String!
            $beneficiary_zip: String!
            # $beneficiary_additional_data: JSON
            # $amount: Float!
            $fee_type_id: ID!
            $urgency_id: ID!
            $operation_type_id: ID!
            $payment_provider_id: ID!
            $respondent_fees_id: ID!
            $company_id: ID!
            # $execution_at: DateTime
        ) {
            createPayment(
                account_id: $account_id
                currency_id: $currency_id
                price_list_fees_id: $price_list_fees_id
                recipient_account: $recipient_account
                recipient_bank_name: $recipient_bank_name
                recipient_bank_address: $recipient_bank_address
                recipient_bank_swift: $recipient_bank_swift
                recipient_bank_country_id: $recipient_bank_country_id
                beneficiary_name: $beneficiary_name
                beneficiary_state: $beneficiary_state
                beneficiary_country_id: $beneficiary_country_id
                beneficiary_address: $beneficiary_address
                beneficiary_city: $beneficiary_city
                beneficiary_zip: $beneficiary_zip
                amount: '.$data['amount'].'
                fee_type_id: $fee_type_id
                urgency_id: $urgency_id
                operation_type_id: $operation_type_id
                company_id: $company_id
                # execution_at: "2023-09-12 11:12:12"
                payment_provider_id: $payment_provider_id
                respondent_fees_id: $respondent_fees_id
            ) {
                amount
                amount_real
                fee
                fee_type {
                    name
                }
                price_list_fees {
                    name
                }
                respondent_fee {
                    name
                }
                recipient_account
                recipient_bank_name
                recipient_bank_address
                recipient_bank_swift
                recipient_bank_country {
                    name
                }
                beneficiary_name
                beneficiary_state
                beneficiary_country {
                    name
                }
                beneficiary_address
                beneficiary_city
                beneficiary_zip
                account {
                    account_name
                }
            }
        }
        ', $data);

        $feeType = FeeType::select('name')->find($data['fee_type_id']);
        $priceListFees = PriceListFee::select('name')->find($data['price_list_fees_id']);
        $respondentFee = RespondentFee::select('name')->find($data['respondent_fees_id']);
        $recipientBankCountry = Country::select('name')->find($data['recipient_bank_country_id']);
        $beneficiaryCountry = Country::select('name')->find($data['beneficiary_country_id']);
        $account = Account::find($this->data['account_id']);

        $this->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /*public function testCreatePayment(): void
    {

        $seq = DB::table('payments')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payments_id_seq RESTART WITH '.$seq);

        $faker = Faker::create();

        $data = [
            'account_id' => $this->data['account_id'],
            'currency_id' => 1,
            'price_list_fees_id' => 1,
            'recipient_account' => $faker->name(),
            'recipient_bank_name' => $faker->name(),
            'recipient_bank_address' => $faker->address(),
            'recipient_bank_swift' => $faker->swiftBicNumber(),
            'recipient_bank_country_id' => 1,
            'beneficiary_name' => $faker->name(),
            'beneficiary_state' => $faker->name(),
            'beneficiary_country_id' => 3,
            'beneficiary_address' => $faker->address(),
            'beneficiary_city' => $faker->city(),
            'beneficiary_zip' => '122112',
            'amount' => (float) 90.00000,
            'amount_real' => (float) 49.00000,
            'fee' => (float) 2.00000,
            'fee_type_id' => 1,
            'urgency_id' => 1,
            'operation_type_id' => 1,
            'payment_provider_id' => 1,
            'respondent_fees_id' => 3,
            'company_id' => 1,
            # 'execution_at' => DateTime
        ];

        $this->postGraphQL([
            'query' => '
            mutation(
                $account_id: ID!
                $currency_id: ID!
                $price_list_fees_id: ID!
                $recipient_account: String!
                $recipient_bank_name: String!
                $recipient_bank_address: String!
                $recipient_bank_swift: String!
                $recipient_bank_country_id: ID!
                $beneficiary_name: String!
                $beneficiary_state: String!
                $beneficiary_country_id: ID!
                $beneficiary_address: String!
                $beneficiary_city: String!
                $beneficiary_zip: String!
                # $beneficiary_additional_data: JSON
                $amount: Decimal!
                $amount_real: Decimal!
                $fee: Decimal
                $fee_type_id: ID!
                $urgency_id: ID!
                $operation_type_id: ID!
                $payment_provider_id: ID!
                $respondent_fees_id: ID!
                $company_id: ID!
                # $execution_at: DateTime
            ) {
                createPayment(
                    account_id: $account_id
                    currency_id: $currency_id
                    price_list_fees_id: $price_list_fees_id
                    recipient_account: $recipient_account
                    recipient_bank_name: $recipient_bank_name
                    recipient_bank_address: $recipient_bank_address
                    recipient_bank_swift: $recipient_bank_swift
                    recipient_bank_country_id: $recipient_bank_country_id
                    beneficiary_name: $beneficiary_name
                    beneficiary_state: $beneficiary_state
                    beneficiary_country_id: $beneficiary_country_id
                    beneficiary_address: $beneficiary_address
                    beneficiary_city: $beneficiary_city
                    beneficiary_zip: $beneficiary_zip
                    amount: $amount
                    amount_real: $amount_real
                    fee: $fee
                    fee_type_id: $fee_type_id
                    urgency_id: $urgency_id
                    operation_type_id: $operation_type_id
                    company_id: $company_id
                    # execution_at: "2023-09-12 11:12:12"
                    payment_provider_id: $payment_provider_id
                    respondent_fees_id: $respondent_fees_id
                ) {
                    amount
                    amount_real
                    fee
                    fee_type {
                        name
                    }
                    price_list_fees {
                        name
                    }
                    respondent_fee {
                        name
                    }
                    recipient_account
                    recipient_bank_name
                    recipient_bank_address
                    recipient_bank_swift
                    recipient_bank_country {
                        name
                    }
                    beneficiary_name
                    beneficiary_state
                    beneficiary_country {
                        name
                    }
                    beneficiary_address
                    beneficiary_city
                    beneficiary_zip
                    account {
                        account_name
                    }
                }
            }',
            'variables' => $data
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $feeType = FeeType::select('name')->find($data['fee_type_id']);
        $priceListFees = PriceListFee::select('name')->find($data['price_list_fees_id']);
        $respondentFee = RespondentFee::select('name')->find($data['respondent_fees_id']);
        $recipientBankCountry = Country::select('name')->find($data['recipient_bank_country_id']);
        $beneficiaryCountry = Country::select('name')->find($data['beneficiary_country_id']);
        $account = Account::find($this->data['account_id']);

        $this->seeJson([
            'data' => [
                'createPayment' => [
                    'amount' => 98.00000,
                    'amount_real' => 59.00000,
                    'fee' => (float) 9.00000,
                    'fee_type' => $feeType,
                    'price_list_fees' => $priceListFees,
                    'respondent_fee' => $respondentFee,
                    'recipient_account' => $data['recipient_account'],
                    'recipient_bank_name' => $data['recipient_bank_name'],
                    'recipient_bank_address' => $data['recipient_bank_address'],
                    'recipient_bank_swift' => $data['recipient_bank_swift'],
                    'recipient_bank_country' => $recipientBankCountry,
                    'beneficiary_name' => $data['beneficiary_name'],
                    'beneficiary_state' => $data['beneficiary_state'],
                    'beneficiary_country' => $beneficiaryCountry,
                    'beneficiary_address' => $data['beneficiary_address'],
                    'beneficiary_city' => $data['beneficiary_city'],
                    'beneficiary_zip' => $data['beneficiary_zip'],
                    'account' => ['account_name' => $account->account_name],
                ],
            ],
        ]);

        $this->seeInDatabase(
            (new Account)->getTable(),
            [
                'current_balance' => 10.00000,
                'reserved_balance' => ($this->amount['fee'] + $this->amount['real']),
                'available_balance' => 10.00000 - ($this->amount['fee'] + $this->amount['real']),
            ]
        );
    }*/

    public function testUpdatePaymentStatusToCompleted(): void
    {
        $payment = DB::connection('pgsql_test')
            ->table('payments')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation(
                    $id: ID!
                    $status_id: ID!
                ) {
                    updatePayment(
                        id: $id
                        status_id: $status_id
                    ) {
                        id
                        status_id
                    }
                }',
                'variables' => [
                    'id' => $payment->id,
                    'status_id' => $payment->status_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $this->seeJson([
            'data' => [
                'updatePayment' => [
                    'id' => (string) $payment->id,
                    'status_id' => (string) $payment->status_id,
                ],
            ],
        ]);
    }

    public function testDeletePayment(): void
    {
        $payment = DB::connection('pgsql_test')
            ->table('payments')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation(
                    $id: ID!
                ) {
                    deletePayment(
                        id: $id
                    ) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $payment->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $this->seeJson([
            'data' => [
                'deletePayment' => [
                    'id' => (string) $payment->id,
                ],
            ],
        ]);
    }
}
