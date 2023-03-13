<?php

namespace Feature\GraphQL\Queries;

use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferIncomingsQueryTest extends TestCase
{
    public function testQueryTransferIncomingsNoAuth(): void
    {
        $this->graphQL('
            {
                transferIncomings {
                    data {
                      id
                      amount
                      amount_debt
                      fee {
                        fee
                        fee_amount
                      }
                      fees {
                        fee
                        fee_pp
                        fee_amount
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
                      transfer_swift {
                        swift
                        bank_name
                        bank_address
                      }
                      execution_at
                    }
                  }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryTransferIncoming(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncoming($id: ID!) {
                    transferIncoming (id: $id) {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                        }
                }',
                'variables' => [
                    'id' => $transferIncoming->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncoming' => [
                    'id' => (string) $transferIncoming->id,
                    'amount' => (string) $transferIncoming->amount,
                    'amount_debt' => (string) $transferIncoming->amount_debt,
                    'fee' => [
                        'fee' => (string) $fee->fee,
                        'fee_amount' => (string) $fee->fee_amount,
                    ],
                    'fees' => [[
                        'fee' => (string) $fees->fee,
                        'fee_pp' => (string) $fees->fee_pp,
                        'fee_amount' => (string) $fees->fee_amount,
                    ]],
                    'files' => [[
                        'id' => (string) $files->id,
                        'file_name' => (string) $files->file_name,
                        'mime_type' => (string) $files->mime_type,
                    ]],
                    'currency' => [
                        'id' => (string) $currency->id,
                        'name' => (string) $currency->name,
                    ],
                    'status' => [
                        'id' => (string) $status->id,
                        'name' => (string) $status->name,
                    ],
                    'payment_urgency' => [
                        'id' => (string) $paymentUrgency->id,
                        'name' => (string) $paymentUrgency->name,
                    ],
                    'payment_operation_type' => [
                        'id' => (string) $paymentOperationType->id,
                        'name' => (string) $paymentOperationType->name,
                    ],
                    'payment_provider' => [
                        'id' => (string) $paymentProvider->id,
                        'name' => (string) $paymentProvider->name,
                        'description' => (string) $paymentProvider->description,
                    ],
                    'payment_provider_history' => [
                        'id' => (string) $paymentProviderHistory->id,
                        'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                        'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                    ],
                    'payment_system' => [
                        'id' => (string) $paymentSystem->id,
                        'name' => (string) $paymentSystem->name,
                    ],
                    'payment_bank' => [
                        'id' => (string) $paymentBank->id,
                        'name' => (string) $paymentBank->name,
                        'address' => (string) $paymentBank->address,
                    ],
                    'payment_number' => $transferIncoming->payment_number,
                    'transfer_type' => [
                        'id' => (string) $transferType->id,
                        'name' => (string) $transferType->name,
                    ],
                    'account' => [
                        'id' => (string) $account->id,
                        'account_type' => (string) $account->account_type,
                    ],
                    'company' => [
                        'id' => (string) $company->id,
                        'name' => (string) $company->name,
                        'email' => (string) $company->email,
                    ],
                    'system_message' => $transferIncoming->system_message,
                    'reason' => $transferIncoming->reason,
                    'channel' => $transferIncoming->channel,
                    'bank_message' => $transferIncoming->bank_message,
                    'sender_account' => $transferIncoming->sender_account,
                    'sender_bank_name' => $transferIncoming->sender_bank_name,
                    'sender_bank_address' => $transferIncoming->sender_bank_address,
                    'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                    'sender_bank_country' => [
                        'id' => (string) $senderBankCountry->id,
                        'name' => (string) $senderBankCountry->name,
                    ],
                    'sender_name' => $transferIncoming->sender_name,
                    'sender_country' => [
                        'id' => (string) $senderCountry->id,
                        'name' => (string) $senderCountry->name,
                    ],
                    'sender_city' => $transferIncoming->sender_city,
                    'sender_address' => $transferIncoming->sender_address,
                    'sender_state' => $transferIncoming->sender_state,
                    'sender_zip' => $transferIncoming->sender_zip,
                    'respondent_fee' => [
                        'id' => (string) $respondentFee->id,
                        'name' => (string) $respondentFee->name,
                    ],
                    'transfer_swift' => [
                        'swift' => (string) $transferSwift->swift,
                        'bank_name' => (string) $transferSwift->bank_name,
                        'bank_address' => (string) $transferSwift->bank_address,
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsList(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferIncomings (first: 1) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }'
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                            [
                                'id' => (string) $transferIncoming->id,
                                'amount' => (string) $transferIncoming->amount,
                                'amount_debt' => (string) $transferIncoming->amount_debt,
                                'fee' => [
                                    'fee' => (string) $fee->fee,
                                    'fee_amount' => (string) $fee->fee_amount,
                                ],
                                'fees' => [[
                                    'fee' => (string) $fees->fee,
                                    'fee_pp' => (string) $fees->fee_pp,
                                    'fee_amount' => (string) $fees->fee_amount,
                                ]],
                                'files' => [[
                                    'id' => (string) $files->id,
                                    'file_name' => (string) $files->file_name,
                                    'mime_type' => (string) $files->mime_type,
                                ]],
                                'currency' => [
                                    'id' => (string) $currency->id,
                                    'name' => (string) $currency->name,
                                ],
                                'status' => [
                                    'id' => (string) $status->id,
                                    'name' => (string) $status->name,
                                ],
                                'payment_urgency' => [
                                    'id' => (string) $paymentUrgency->id,
                                    'name' => (string) $paymentUrgency->name,
                                ],
                                'payment_operation_type' => [
                                    'id' => (string) $paymentOperationType->id,
                                    'name' => (string) $paymentOperationType->name,
                                ],
                                'payment_provider' => [
                                    'id' => (string) $paymentProvider->id,
                                    'name' => (string) $paymentProvider->name,
                                    'description' => (string) $paymentProvider->description,
                                ],
                                'payment_provider_history' => [
                                    'id' => (string) $paymentProviderHistory->id,
                                    'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                    'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                                ],
                                'payment_system' => [
                                    'id' => (string) $paymentSystem->id,
                                    'name' => (string) $paymentSystem->name,
                                ],
                                'payment_bank' => [
                                    'id' => (string) $paymentBank->id,
                                    'name' => (string) $paymentBank->name,
                                    'address' => (string) $paymentBank->address,
                                ],
                                'payment_number' => $transferIncoming->payment_number,
                                'transfer_type' => [
                                    'id' => (string) $transferType->id,
                                    'name' => (string) $transferType->name,
                                ],
                                'account' => [
                                    'id' => (string) $account->id,
                                    'account_type' => (string) $account->account_type,
                                ],
                                'company' => [
                                    'id' => (string) $company->id,
                                    'name' => (string) $company->name,
                                    'email' => (string) $company->email,
                                ],
                                'system_message' => $transferIncoming->system_message,
                                'reason' => $transferIncoming->reason,
                                'channel' => $transferIncoming->channel,
                                'bank_message' => $transferIncoming->bank_message,
                                'sender_account' => $transferIncoming->sender_account,
                                'sender_bank_name' => $transferIncoming->sender_bank_name,
                                'sender_bank_address' => $transferIncoming->sender_bank_address,
                                'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                                'sender_bank_country' => [
                                    'id' => (string) $senderBankCountry->id,
                                    'name' => (string) $senderBankCountry->name,
                                ],
                                'sender_name' => $transferIncoming->sender_name,
                                'sender_country' => [
                                    'id' => (string) $senderCountry->id,
                                    'name' => (string) $senderCountry->name,
                                ],
                                'sender_city' => $transferIncoming->sender_city,
                                'sender_address' => $transferIncoming->sender_address,
                                'sender_state' => $transferIncoming->sender_state,
                                'sender_zip' => $transferIncoming->sender_zip,
                                'respondent_fee' => [
                                    'id' => (string) $respondentFee->id,
                                    'name' => (string) $respondentFee->name,
                                ],
                                'transfer_swift' => [
                                    'swift' => (string) $transferSwift->swift,
                                    'bank_name' => (string) $transferSwift->bank_name,
                                    'bank_address' => (string) $transferSwift->bank_address,
                                ],
                            ],
                        ],
                    ],
                ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterById(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($id: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: ID
                            value: $id
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                   'id' => $transferIncoming->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByUrgencyId(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($id: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: URGENCY_ID
                            value: $id
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'id' => $transferIncoming->urgency_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByOperationTypeId(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($id: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: OPERATION_TYPE_ID
                            value: $id
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'id' => $transferIncoming->operation_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByStatusId(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($id: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: STATUS_ID
                            value: $id
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'id' => $transferIncoming->status_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterBySenderName(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($string: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: SENDER_NAME
                            operator: ILIKE
                            value: $string
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'string' => $transferIncoming->sender_name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByAccountNumber(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($string: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: HAS_ACCOUNT_FILTER_BY_ACCOUNT_NUMBER
                            operator: ILIKE
                            value: $string
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'string' => $account->account_number,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByFee(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($string: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: HAS_FEE_FILTER_BY_FEE
                            operator: ILIKE
                            value: $string
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'string' => $fee->fee,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingsWithFilterByFeeAmount(): void
    {
        $this->markTestSkipped('Skipped');
        $transferIncoming = TransferIncoming::orderBy('id', 'ASC')->first();

        $fee = $transferIncoming->fee()->first();
        $fees = $transferIncoming->fees()->first();
        $files = $transferIncoming->files()->first();
        $currency = $transferIncoming->currency()->first();
        $status = $transferIncoming->paymentStatus()->first();
        $paymentUrgency = $transferIncoming->paymentUrgency()->first();
        $paymentOperationType = $transferIncoming->paymentOperation()->first();
        $paymentProvider = $transferIncoming->paymentProvider()->first();
        $paymentProviderHistory = $transferIncoming->paymentProviderHistory()->first();
        $paymentSystem = $transferIncoming->paymentSystem()->first();
        $paymentBank = $transferIncoming->paymentBank()->first();
        $transferType = $transferIncoming->transferType()->first();
        $account = $transferIncoming->account()->first();
        $company = $transferIncoming->company()->first();
        $senderBankCountry = $transferIncoming->senderBankCountry()->first();
        $senderCountry = $transferIncoming->senderCountry()->first();
        $respondentFee = $transferIncoming->respondentFee()->first();
        $transferSwift = $transferIncoming->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferIncomings($string: Mixed) {
                    transferIncomings (
                        first: 1
                        filter: {
                            column: HAS_FEE_FILTER_BY_FEE_AMOUNT
                            operator: ILIKE
                            value: $string
                        }
                    ) {
                      data {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
                            fee_pp
                            fee_amount
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
                          transfer_type {
                            id
                            name
                          }
                          account {
                            id
                            account_type
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
                          transfer_swift {
                            swift
                            bank_name
                            bank_address
                          }
                          }
                        }
                }',
                'variables' => [
                    'string' => $fee->fee_amount,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferIncomings' => [
                    'data' => [
                        [
                            'id' => (string) $transferIncoming->id,
                            'amount' => (string) $transferIncoming->amount,
                            'amount_debt' => (string) $transferIncoming->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
                                'fee_pp' => (string) $fees->fee_pp,
                                'fee_amount' => (string) $fees->fee_amount,
                            ]],
                            'files' => [[
                                'id' => (string) $files->id,
                                'file_name' => (string) $files->file_name,
                                'mime_type' => (string) $files->mime_type,
                            ]],
                            'currency' => [
                                'id' => (string) $currency->id,
                                'name' => (string) $currency->name,
                            ],
                            'status' => [
                                'id' => (string) $status->id,
                                'name' => (string) $status->name,
                            ],
                            'payment_urgency' => [
                                'id' => (string) $paymentUrgency->id,
                                'name' => (string) $paymentUrgency->name,
                            ],
                            'payment_operation_type' => [
                                'id' => (string) $paymentOperationType->id,
                                'name' => (string) $paymentOperationType->name,
                            ],
                            'payment_provider' => [
                                'id' => (string) $paymentProvider->id,
                                'name' => (string) $paymentProvider->name,
                                'description' => (string) $paymentProvider->description,
                            ],
                            'payment_provider_history' => [
                                'id' => (string) $paymentProviderHistory->id,
                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $paymentSystem->id,
                                'name' => (string) $paymentSystem->name,
                            ],
                            'payment_bank' => [
                                'id' => (string) $paymentBank->id,
                                'name' => (string) $paymentBank->name,
                                'address' => (string) $paymentBank->address,
                            ],
                            'payment_number' => $transferIncoming->payment_number,
                            'transfer_type' => [
                                'id' => (string) $transferType->id,
                                'name' => (string) $transferType->name,
                            ],
                            'account' => [
                                'id' => (string) $account->id,
                                'account_type' => (string) $account->account_type,
                            ],
                            'company' => [
                                'id' => (string) $company->id,
                                'name' => (string) $company->name,
                                'email' => (string) $company->email,
                            ],
                            'system_message' => $transferIncoming->system_message,
                            'reason' => $transferIncoming->reason,
                            'channel' => $transferIncoming->channel,
                            'bank_message' => $transferIncoming->bank_message,
                            'sender_account' => $transferIncoming->sender_account,
                            'sender_bank_name' => $transferIncoming->sender_bank_name,
                            'sender_bank_address' => $transferIncoming->sender_bank_address,
                            'sender_bank_swift' => $transferIncoming->sender_bank_swift,
                            'sender_bank_country' => [
                                'id' => (string) $senderBankCountry->id,
                                'name' => (string) $senderBankCountry->name,
                            ],
                            'sender_name' => $transferIncoming->sender_name,
                            'sender_country' => [
                                'id' => (string) $senderCountry->id,
                                'name' => (string) $senderCountry->name,
                            ],
                            'sender_city' => $transferIncoming->sender_city,
                            'sender_address' => $transferIncoming->sender_address,
                            'sender_state' => $transferIncoming->sender_state,
                            'sender_zip' => $transferIncoming->sender_zip,
                            'respondent_fee' => [
                                'id' => (string) $respondentFee->id,
                                'name' => (string) $respondentFee->name,
                            ],
                            'transfer_swift' => [
                                'swift' => (string) $transferSwift->swift,
                                'bank_name' => (string) $transferSwift->bank_name,
                                'bank_address' => (string) $transferSwift->bank_address,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryTransferIncomingStatistic(): void
    {
        $this->markTestSkipped('Skipped');
        $statistics = TransferIncoming::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
        ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferIncomingsStatistic {
                        status_id
                        name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'status_id' => (string) $statistics->status_id,
            'name' => (string) $statistics->name,
            'count' => $statistics->count,
        ]);
    }

    public function testQueryTransferIncomingStatisticWithCompanyId(): void
    {
        $this->markTestSkipped('Skipped');
        $statistics = TransferIncoming::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferIncomingsStatistic (company_id: 1) {
                        status_id
                        name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'status_id' => (string) $statistics->status_id,
            'name' => (string) $statistics->name,
            'count' => $statistics->count,
        ]);
    }

    public function testQueryTransferIncomingStatisticWithPaymentProviderId(): void
    {
        $this->markTestSkipped('Skipped');
        $statistics = TransferIncoming::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferIncomingsStatistic (payment_provider_id: 1) {
                        status_id
                        name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'status_id' => (string) $statistics->status_id,
            'name' => (string) $statistics->name,
            'count' => $statistics->count,
        ]);
    }

    public function testQueryTransferIncomingStatisticWithAccountId(): void
    {
        $this->markTestSkipped('Skipped');
        $statistics = TransferIncoming::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_incomings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferIncomingsStatistic (account_id: 1) {
                        status_id
                        name
                        count
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'status_id' => (string) $statistics->status_id,
            'name' => (string) $statistics->name,
            'count' => $statistics->count,
        ]);
    }
}
