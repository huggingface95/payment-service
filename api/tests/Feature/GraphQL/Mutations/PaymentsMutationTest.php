<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Enums\PaymentStatusEnum;
use App\Models\Account;
use App\Models\Country;
use App\Models\FeeType;
use App\Models\PriceListFee;
use App\Models\RespondentFee;
use Faker\Factory as Faker;
use Tests\TestCase;

class PaymentsMutationTest extends TestCase
{
    private $data = [
        'account_id' => 1,
    ];

    public function setUp(): void
    {
        parent::setUp();

        Account::where('id', $this->data['account_id'])->update(['current_balance' => 10000, 'available_balance' => 10000]);
    }

    public function testCreatePayment(): void
    {
        $this->login();

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
            # 'execution_at' => DateTime
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
                amount: ' . $data['amount'] . '
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
            'data' => [
                'createPayment' => [
                    'amount' => $data['amount'],
                    'amount_real' => 811.5,
                    'fee' => 377,
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
                'current_balance' => 10000.00000,
                'reserved_balance' => 1188.50000,
                'available_balance' => 8811.50000,
            ]
        );
    }

    public function testUpdatePaymentStatusToCompleted(): void
    {
        $this->login();

        $data = [
            'id' => 1,
            'status_id' => PaymentStatusEnum::COMPLETED->value,
        ];

        $this->graphQL('
        mutation(
            $id: ID!
        ) {
            updatePayment(
                id: $id
                status_id: ' . $data['status_id'] . '
            ) {
                amount
                amount_real
                fee
            }
        }
        ', $data);

        $this->seeJson([
            'data' => [
                'updatePayment' => [
                    'amount' => 1000,
                    'amount_real' => 811.5,
                    'fee' => 377,
                ],
            ],
        ]);

        $this->seeInDatabase(
            (new Account)->getTable(),
            [
                'current_balance' => 8811.50000,
                'reserved_balance' => 0.00000,
                'available_balance' => 8811.50000,
            ]
        );
    }

    public function testDeletePayment(): void
    {
        $this->login();

        $data = [
            'id' => 1,
        ];

        $this->graphQL('
        mutation(
            $id: ID!
        ) {
            deletePayment(
                id: $id
            ) {
                amount
                amount_real
                fee
            }
        }
        ', $data);

        $this->seeJson([
            'data' => [
                'deletePayment' => [
                    'amount' => 1000,
                    'amount_real' => 811.5,
                    'fee' => 377,
                ],
            ],
        ]);

    }

}
