<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferIncomingsMutationTest extends TestCase
{
    /**
     * TransferIncomings Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferIncomingNoAuth(): void
    {
        $this->graphQL('
            mutation CreateTransferIncoming(
                $group_id: ID!
                $group_type_id: ID!
                $project_id: ID!
                $currency_id: ID!
                $account_id: ID!
                $payment_provider_id: ID!
                $payment_system_id: ID!
                $payment_bank_id: ID!
                $price_list_id: ID!
                $price_list_fee_id: ID!
                $beneficiary_type: BeneficiaryTypeEnum!
                $beneficiary_name: String!
                $sender_account: String
                $sender_bank_name: String
                $sender_bank_address: String
                $sender_bank_swift: String
                $sender_bank_country_id: ID
                $sender_name: String
                $sender_country_id: ID!
                $sender_city: String
                $sender_address: String
                $sender_state: String
                $sender_zip: String
                $bank_message: String
            )
            {
                createTransferIncoming (
                    group_id: $group_id
                    group_type_id: $group_type_id
                    project_id: $project_id
                    amount: 10
                    currency_id: $currency_id
                    account_id: $account_id
                    payment_provider_id: $payment_provider_id
                    payment_system_id: $payment_system_id
                    payment_bank_id: $payment_bank_id
                    price_list_id: $price_list_id
                    price_list_fee_id: $price_list_fee_id
                    beneficiary_type: $beneficiary_type
                    beneficiary_name: $beneficiary_name
                    sender_account: $sender_account
                    sender_bank_name: $sender_bank_name
                    sender_bank_address: $sender_bank_address
                    sender_bank_swift: $sender_bank_swift
                    sender_bank_country_id: $sender_bank_country_id
                    sender_name: $sender_name
                    sender_country_id: $sender_country_id
                    sender_city: $sender_city
                    sender_address: $sender_address
                    sender_state: $sender_state
                    sender_zip: $sender_zip
                    bank_message: $bank_message
                )
                {
                    id
                      amount
                      amount_debt
                      fee_amount
                      fee {
                        fee
                      }
                      fees {
                        fee
                      }
                      files {
                        id
                        file_name
                        mime_type
                      }
                      currency {
                        id
                        name
                      }
                      status {
                        id
                        name
                      }
                      payment_urgency {
                        id
                        name
                      }
                      payment_operation_type {
                        id
                        name
                      }
                      payment_provider {
                        id
                        name
                        description
                      }
                      payment_provider_history {
                        id
                        payment_provider_id
                        transfer_id
                      }
                      payment_system {
                        id
                        name
                      }
                      payment_bank {
                        id
                        name
                        address
                      }
                      payment_number
                      payment_operation_type {
                        id
                        name
                      }
                      transfer_type {
                        id
                        name
                      }
                      account {
                        id
                        account_type
                      }
                      recipient {
                        __typename
                      }
                      company {
                        id
                        name
                        email
                      }
                      system_message
                      reason
                      channel
                      bank_message
                      sender_account
                      sender_bank_name
                      sender_bank_address
                      sender_bank_swift
                      sender_bank_country {
                        id
                        name
                      }
                      sender_name
                      sender_country {
                        id
                        name
                      }
                      sender_city
                      sender_address
                      sender_state
                      sender_zip
                      respondent_fee {
                        id
                        name
                      }
                }
            }
        ', [
            'group_id' => 1,
            'group_type_id' => 1,
            'project_id' => 1,
            'currency_id' => 1,
            'account_id' => 1,
            'payment_provider_id' => 1,
            'payment_system_id' => 1,
            'payment_bank_id' => 1,
            'price_list_id' => 1,
            'price_list_fee_id' => 1,
            'beneficiary_type' => 'Personal',
            'beneficiary_name' => 'Beneficiary Test Name',
            'sender_account' => 'Sender Account',
            'sender_bank_name' => 'sender_bank_name',
            'sender_bank_address' => 'sender_bank_address',
            'sender_bank_swift' => 'sender_bank_swift',
            'sender_bank_country_id' => 4,
            'sender_name' => 'sender_name',
            'sender_country_id' => 5,
            'sender_city' => 'sender_city',
            'sender_address' => 'sender_address',
            'sender_state' => 'sender_state',
            'sender_zip' => 'sender_zip',
            'bank_message' => 'bank_message',
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferIncoming(): void
    {
        $seq = DB::table('transfer_incomings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_incomings_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateTransferIncoming(
                $group_id: ID!
                $group_type_id: ID!
                $project_id: ID!
                $currency_id: ID!
                $account_id: ID!
                $payment_provider_id: ID!
                $payment_system_id: ID!
                $payment_bank_id: ID!
                $price_list_id: ID!
                $price_list_fee_id: ID!
                $beneficiary_type: BeneficiaryTypeEnum!
                $beneficiary_name: String!
                $sender_account: String
                $sender_bank_name: String
                $sender_bank_address: String
                $sender_bank_swift: String
                $sender_bank_country_id: ID
                $sender_name: String
                $sender_country_id: ID!
                $sender_city: String
                $sender_address: String
                $sender_state: String
                $sender_zip: String
                $bank_message: String
                $urgency_id: ID
                $respondent_fees_id: ID
            )
            {
                createTransferIncoming (
                    group_id: $group_id
                    group_type_id: $group_type_id
                    project_id: $project_id
                    amount: 10
                    currency_id: $currency_id
                    account_id: $account_id
                    payment_provider_id: $payment_provider_id
                    payment_system_id: $payment_system_id
                    payment_bank_id: $payment_bank_id
                    price_list_id: $price_list_id
                    price_list_fee_id: $price_list_fee_id
                    beneficiary_type: $beneficiary_type
                    beneficiary_name: $beneficiary_name
                    sender_account: $sender_account
                    sender_bank_name: $sender_bank_name
                    sender_bank_address: $sender_bank_address
                    sender_bank_swift: $sender_bank_swift
                    sender_bank_country_id: $sender_bank_country_id
                    sender_name: $sender_name
                    sender_country_id: $sender_country_id
                    sender_city: $sender_city
                    sender_address: $sender_address
                    sender_state: $sender_state
                    sender_zip: $sender_zip
                    bank_message: $bank_message
                    urgency_id: $urgency_id
                    respondent_fees_id: $respondent_fees_id
                )
                {
                      id
                      amount
                      amount_debt
                      payment_number
                      system_message
                      reason
                      channel
                      bank_message
                      sender_account
                      sender_bank_name
                      sender_bank_address
                      sender_bank_swift
                      sender_name
                      sender_city
                      sender_address
                      sender_state
                      sender_zip
                      payment_urgency {
                        id
                        name
                      }
                      respondent_fee {
                        id
                        name
                      }
                      beneficiary_type_id
                      beneficiary_name
                }
                }',
                'variables' => [
                    'group_id' => 1,
                    'group_type_id' => 1,
                    'project_id' => 1,
                    'currency_id' => 1,
                    'account_id' => 1,
                    'payment_provider_id' => 1,
                    'payment_system_id' => 1,
                    'payment_bank_id' => 1,
                    'price_list_id' => 1,
                    'price_list_fee_id' => 3,
                    'beneficiary_type' => 'Personal',
                    'beneficiary_name' => 'Beneficiary Test Name',
                    'sender_account' => 'Sender Account',
                    'sender_bank_name' => 'sender_bank_name',
                    'sender_bank_address' => 'sender_bank_address',
                    'sender_bank_swift' => 'sender_bank_swift',
                    'sender_bank_country_id' => 4,
                    'sender_name' => 'sender_name',
                    'sender_country_id' => 5,
                    'sender_city' => 'sender_city',
                    'sender_address' => 'sender_address',
                    'sender_state' => 'sender_state',
                    'sender_zip' => 'sender_zip',
                    'bank_message' => 'bank_message',
                    'urgency_id' => 1,
                    'respondent_fees_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferIncoming' => [
                    'id' => $id['data']['createTransferIncoming']['id'],
                    'amount' => $id['data']['createTransferIncoming']['amount'],
                    'amount_debt' => $id['data']['createTransferIncoming']['amount_debt'],
                    'payment_number' => $id['data']['createTransferIncoming']['payment_number'],
                    'system_message' => $id['data']['createTransferIncoming']['system_message'],
                    'reason' => $id['data']['createTransferIncoming']['reason'],
                    'channel' => $id['data']['createTransferIncoming']['channel'],
                    'bank_message' => $id['data']['createTransferIncoming']['bank_message'],
                    'sender_account' => $id['data']['createTransferIncoming']['sender_account'],
                    'sender_bank_name' => $id['data']['createTransferIncoming']['sender_bank_name'],
                    'sender_bank_address' => $id['data']['createTransferIncoming']['sender_bank_address'],
                    'sender_bank_swift' => $id['data']['createTransferIncoming']['sender_bank_swift'],
                    'sender_name' => $id['data']['createTransferIncoming']['sender_name'],
                    'sender_city' => $id['data']['createTransferIncoming']['sender_city'],
                    'sender_address' => $id['data']['createTransferIncoming']['sender_address'],
                    'sender_state' => $id['data']['createTransferIncoming']['sender_state'],
                    'sender_zip' => $id['data']['createTransferIncoming']['sender_zip'],
                    'payment_urgency' => $id['data']['createTransferIncoming']['payment_urgency'],
                    'respondent_fee' => $id['data']['createTransferIncoming']['respondent_fee'],
                    'beneficiary_type_id' => $id['data']['createTransferIncoming']['beneficiary_type_id'],
                    'beneficiary_name' => $id['data']['createTransferIncoming']['beneficiary_name'],
                ],
            ],
        ]);
    }

    public function testUpdateTransferIncoming(): void
    {
        $transferIncoming = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateTransferIncoming(
                $id: ID!
                $group_id: ID!
                $group_type_id: ID!
            )
            {
                updateTransferIncoming (
                    id: $id
                    group_id: $group_id
                    group_type_id: $group_type_id
                )
                {
                      id
                      amount
                      amount_debt
                      payment_number
                      system_message
                      reason
                      channel
                      bank_message
                      sender_account
                      sender_bank_name
                      sender_bank_address
                      sender_bank_swift
                      sender_name
                      sender_city
                      sender_address
                      sender_state
                      sender_zip
                      payment_urgency {
                        id
                        name
                      }
                      respondent_fee {
                        id
                        name
                      }
                      beneficiary_type_id
                      beneficiary_name
                }
                }',
                'variables' => [
                    'id' => $transferIncoming->id,
                    'group_id' => 1,
                    'group_type_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateTransferIncoming' => [
                    'id' => $id['data']['updateTransferIncoming']['id'],
                    'amount' => $id['data']['updateTransferIncoming']['amount'],
                    'amount_debt' => $id['data']['updateTransferIncoming']['amount_debt'],
                    'payment_number' => $id['data']['updateTransferIncoming']['payment_number'],
                    'system_message' => $id['data']['updateTransferIncoming']['system_message'],
                    'reason' => $id['data']['updateTransferIncoming']['reason'],
                    'channel' => $id['data']['updateTransferIncoming']['channel'],
                    'bank_message' => $id['data']['updateTransferIncoming']['bank_message'],
                    'sender_account' => $id['data']['updateTransferIncoming']['sender_account'],
                    'sender_bank_name' => $id['data']['updateTransferIncoming']['sender_bank_name'],
                    'sender_bank_address' => $id['data']['updateTransferIncoming']['sender_bank_address'],
                    'sender_bank_swift' => $id['data']['updateTransferIncoming']['sender_bank_swift'],
                    'sender_name' => $id['data']['updateTransferIncoming']['sender_name'],
                    'sender_city' => $id['data']['updateTransferIncoming']['sender_city'],
                    'sender_address' => $id['data']['updateTransferIncoming']['sender_address'],
                    'sender_state' => $id['data']['updateTransferIncoming']['sender_state'],
                    'sender_zip' => $id['data']['updateTransferIncoming']['sender_zip'],
                    'payment_urgency' => $id['data']['updateTransferIncoming']['payment_urgency'],
                    'respondent_fee' => $id['data']['updateTransferIncoming']['respondent_fee'],
                    'beneficiary_type_id' => $id['data']['updateTransferIncoming']['beneficiary_type_id'],
                    'beneficiary_name' => $id['data']['updateTransferIncoming']['beneficiary_name'],
                ],
            ],
        ]);
    }
}
