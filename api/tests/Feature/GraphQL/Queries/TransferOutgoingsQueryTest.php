<?php

namespace Feature\GraphQL\Queries;

use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferOutgoingsQueryTest extends TestCase
{
    public function testQueryTransferOutgoingsNoAuth(): void
    {
        $this->graphQL('
            {
                  transferOutgoings {
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
                      company {
                        id
                        name
                        email
                      }
                      system_message
                      reason
                      channel
                      bank_message
                      recipient_account
                      recipient_bank_name
                      recipient_bank_address
                      recipient_bank_swift
                      recipient_bank_country {
                        id
                        name
                      }
                      recipient_name
                      recipient_country {
                        id
                        name
                      }
                      recipient_city
                      recipient_address
                      recipient_state
                      recipient_zip
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

    public function testQueryTransferOutgoing(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query TransferOutgoing($id: ID!) {
                    transferOutgoing (id: $id) {
                          id
                          amount
                          amount_debt
                          fee {
                            fee
                            fee_amount
                          }
                          fees {
                            fee
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoing' => [
                    'id' => (string) $transferOutgoing->id,
                    'amount' => (string) $transferOutgoing->amount,
                    'amount_debt' => (string) $transferOutgoing->amount_debt,
                    'fee' => [
                        'fee' => (string) $fee->fee,
                        'fee_amount' => (string) $fee->fee_amount,
                    ],
                    'fees' => [[
                        'fee' => (string) $fees->fee,
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
                    'payment_number' => $transferOutgoing->payment_number,
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
                    'system_message' => $transferOutgoing->system_message,
                    'reason' => $transferOutgoing->reason,
                    'channel' => $transferOutgoing->channel,
                    'bank_message' => $transferOutgoing->bank_message,
                    'recipient_account' => $transferOutgoing->recipient_account,
                    'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                    'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                    'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                    'recipient_bank_country' => [
                        'id' => (string) $recipientBankCountry->id,
                        'name' => (string) $recipientBankCountry->name,
                    ],
                    'recipient_name' => $transferOutgoing->recipient_name,
                    'recipient_country' => [
                        'id' => (string) $recipientCountry->id,
                        'name' => (string) $recipientCountry->name,
                    ],
                    'recipient_city' => $transferOutgoing->recipient_city,
                    'recipient_address' => $transferOutgoing->recipient_address,
                    'recipient_state' => $transferOutgoing->recipient_state,
                    'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsList(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferOutgoings (filter: {column: ID, value: 2}) {
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                'transferOutgoings' => [
                    'data' => [
                            [
                                'id' => (string) $transferOutgoing->id,
                                'amount' => (string) $transferOutgoing->amount,
                                'amount_debt' => (string) $transferOutgoing->amount_debt,
                                'fee' => [
                                    'fee' => (string) $fee->fee,
                                    'fee_amount' => (string) $fee->fee_amount,
                                ],
                                'fees' => [[
                                    'fee' => (string) $fees->fee,
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
                                'payment_number' => $transferOutgoing->payment_number,
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
                                'system_message' => $transferOutgoing->system_message,
                                'reason' => $transferOutgoing->reason,
                                'channel' => $transferOutgoing->channel,
                                'bank_message' => $transferOutgoing->bank_message,
                                'recipient_account' => $transferOutgoing->recipient_account,
                                'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                                'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                                'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                                'recipient_bank_country' => [
                                    'id' => (string) $recipientBankCountry->id,
                                    'name' => (string) $recipientBankCountry->name,
                                ],
                                'recipient_name' => $transferOutgoing->recipient_name,
                                'recipient_country' => [
                                    'id' => (string) $recipientCountry->id,
                                    'name' => (string) $recipientCountry->name,
                                ],
                                'recipient_city' => $transferOutgoing->recipient_city,
                                'recipient_address' => $transferOutgoing->recipient_address,
                                'recipient_state' => $transferOutgoing->recipient_state,
                                'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterById(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed) {
                    transferOutgoings (
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByUrgencyId(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $urgency_id: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: URGENCY_ID, value: $urgency_id }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'urgency_id' => $transferOutgoing->urgency_id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByOperationTypeId(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $operation_type_id: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: OPERATION_TYPE_ID, value: $operation_type_id }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'operation_type_id' => $transferOutgoing->operation_type_id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByStatusId(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $status_id: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: STATUS_ID, value: $status_id }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'status_id' => $transferOutgoing->status_id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByRecipientName(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $recipient_name: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: RECIPIENT_NAME, operator: ILIKE, value: $recipient_name }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'recipient_name' => (string) $transferOutgoing->recipient_name,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByUserType(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $user_type: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: USER_TYPE, value: $user_type }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'user_type' => (string) $transferOutgoing->user_type,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByAccount(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $account_number: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: HAS_ACCOUNT_FILTER_BY_ACCOUNT_NUMBER, operator: ILIKE, value: $account_number }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'account_number' => (string) $account->account_number,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterBySender(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $sender: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: HAS_SENDER_MIXED_NAME_OR_FULLNAME, operator: ILIKE, value: $sender }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'sender' => (string) $transferOutgoing->sender_type,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

    public function testQueryTransferOutgoingsWithFilterByFee(): void
    {
        $transferOutgoing = TransferOutgoing::where('id', 2)->first();

        $fee = $transferOutgoing->feeModeBase()->first();
        $fees = $transferOutgoing->fees()->first();
        $files = $transferOutgoing->files()->first();
        $currency = $transferOutgoing->currency()->first();
        $status = $transferOutgoing->paymentStatus()->first();
        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
        $paymentProvider = $transferOutgoing->paymentProvider()->first();
        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
        $paymentSystem = $transferOutgoing->paymentSystem()->first();
        $paymentBank = $transferOutgoing->paymentBank()->first();
        $transferType = $transferOutgoing->transferType()->first();
        $account = $transferOutgoing->account()->first();
        $company = $transferOutgoing->company()->first();
        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
        $recipientCountry = $transferOutgoing->recipientCountry()->first();
        $respondentFee = $transferOutgoing->respondentFee()->first();
        $transferSwift = $transferOutgoing->transferSwift()->first();

        $this->postGraphQL(
            [
                'query' => '
                query transferOutgoings($id: Mixed, $fee: Mixed) {
                    transferOutgoings (
                        filter: {
                            AND: [{ column: ID, value: $id }, { column: HAS_FEE_FILTER_BY_FEE, operator: ILIKE, value: $fee }]
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
                          company {
                            id
                            name
                            email
                          }
                          system_message
                          reason
                          channel
                          bank_message
                          recipient_account
                          recipient_bank_name
                          recipient_bank_address
                          recipient_bank_swift
                          recipient_bank_country {
                            id
                            name
                          }
                          recipient_name
                          recipient_country {
                            id
                            name
                          }
                          recipient_city
                          recipient_address
                          recipient_state
                          recipient_zip
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
                    'id' => $transferOutgoing->id,
                    'fee' => $fee->fee,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'transferOutgoings' => [
                    'data' => [
                        [
                            'id' => (string) $transferOutgoing->id,
                            'amount' => (string) $transferOutgoing->amount,
                            'amount_debt' => (string) $transferOutgoing->amount_debt,
                            'fee' => [
                                'fee' => (string) $fee->fee,
                                'fee_amount' => (string) $fee->fee_amount,
                            ],
                            'fees' => [[
                                'fee' => (string) $fees->fee,
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
                            'payment_number' => $transferOutgoing->payment_number,
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
                            'system_message' => $transferOutgoing->system_message,
                            'reason' => $transferOutgoing->reason,
                            'channel' => $transferOutgoing->channel,
                            'bank_message' => $transferOutgoing->bank_message,
                            'recipient_account' => $transferOutgoing->recipient_account,
                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
                            'recipient_bank_country' => [
                                'id' => (string) $recipientBankCountry->id,
                                'name' => (string) $recipientBankCountry->name,
                            ],
                            'recipient_name' => $transferOutgoing->recipient_name,
                            'recipient_country' => [
                                'id' => (string) $recipientCountry->id,
                                'name' => (string) $recipientCountry->name,
                            ],
                            'recipient_city' => $transferOutgoing->recipient_city,
                            'recipient_address' => $transferOutgoing->recipient_address,
                            'recipient_state' => $transferOutgoing->recipient_state,
                            'recipient_zip' => $transferOutgoing->recipient_zip,
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

//    public function testQueryTransferOutgoingsWithFilterByFeeAmount(): void
//    {
//        $transferOutgoing = TransferOutgoing::where('id', 2)->first();
//
//        $fee = $transferOutgoing->feeModeBase()->first();
//        $fees = $transferOutgoing->fees()->first();
//        $files = $transferOutgoing->files()->first();
//        $currency = $transferOutgoing->currency()->first();
//        $status = $transferOutgoing->paymentStatus()->first();
//        $paymentUrgency = $transferOutgoing->paymentUrgency()->first();
//        $paymentOperationType = $transferOutgoing->paymentOperation()->first();
//        $paymentProvider = $transferOutgoing->paymentProvider()->first();
//        $paymentProviderHistory = $transferOutgoing->paymentProviderHistory()->first();
//        $paymentSystem = $transferOutgoing->paymentSystem()->first();
//        $paymentBank = $transferOutgoing->paymentBank()->first();
//        $transferType = $transferOutgoing->transferType()->first();
//        $account = $transferOutgoing->account()->first();
//        $company = $transferOutgoing->company()->first();
//        $recipientBankCountry = $transferOutgoing->recipientBankCountry()->first();
//        $recipientCountry = $transferOutgoing->recipientCountry()->first();
//        $respondentFee = $transferOutgoing->respondentFee()->first();
//        $transferSwift = $transferOutgoing->transferSwift()->first();
//
//        $this->postGraphQL(
//            [
//                'query' => '
//                query transferOutgoings($id: Mixed, $fee: Mixed) {
//                    transferOutgoings (
//                        filter: {
//                            AND: [{ column: ID, value: $id }, { column: HAS_FEE_FILTER_BY_FEE_AMOUNT, operator: ILIKE, value: $fee }]
//                        }
//                    ) {
//                      data {
//                          id
//                          amount
//                          amount_debt
//                          fee {
//                            fee
//                            fee_amount
//                          }
//                          fees {
//                            fee
//                            fee_amount
//                          }
//                          files {
//                            id
//                            file_name
//                            mime_type
//                          }
//                          currency {
//                            id
//                            name
//                          }
//                          status {
//                            id
//                            name
//                          }
//                          payment_urgency {
//                            id
//                            name
//                          }
//                          payment_operation_type {
//                            id
//                            name
//                          }
//                          payment_provider {
//                            id
//                            name
//                            description
//                          }
//                          payment_provider_history {
//                            id
//                            payment_provider_id
//                            transfer_id
//                          }
//                          payment_system {
//                            id
//                            name
//                          }
//                          payment_bank {
//                            id
//                            name
//                            address
//                          }
//                          payment_number
//                          payment_operation_type {
//                            id
//                            name
//                          }
//                          transfer_type {
//                            id
//                            name
//                          }
//                          account {
//                            id
//                            account_type
//                          }
//                          company {
//                            id
//                            name
//                            email
//                          }
//                          system_message
//                          reason
//                          channel
//                          bank_message
//                          recipient_account
//                          recipient_bank_name
//                          recipient_bank_address
//                          recipient_bank_swift
//                          recipient_bank_country {
//                            id
//                            name
//                          }
//                          recipient_name
//                          recipient_country {
//                            id
//                            name
//                          }
//                          recipient_city
//                          recipient_address
//                          recipient_state
//                          recipient_zip
//                          respondent_fee {
//                            id
//                            name
//                          }
//                          transfer_swift {
//                            swift
//                            bank_name
//                            bank_address
//                          }
//                      }
//                   }
//                }',
//                'variables' => [
//                    'id' => $transferOutgoing->id,
//                    'fee' => $fee->fee_amount,
//                ]
//            ],
//            [
//                'Authorization' => 'Bearer ' . $this->login(),
//            ]
//        )->seeJson([
//            'data' => [
//                'transferOutgoings' => [
//                    'data' => [
//                        [
//                            'id' => (string) $transferOutgoing->id,
//                            'amount' => (string) $transferOutgoing->amount,
//                            'amount_debt' => (string) $transferOutgoing->amount_debt,
//                            'fee' => [
//                                'fee' => (string) $fee->fee,
//                                'fee_amount' => (string) $fee->fee_amount,
//                            ],
//                            'fees' => [[
//                                'fee' => (string) $fees->fee,
//                                'fee_amount' => (string) $fees->fee_amount,
//                            ]],
//                            'files' => [[
//                                'id' => (string) $files->id,
//                                'file_name' => (string) $files->file_name,
//                                'mime_type' => (string) $files->mime_type,
//                            ]],
//                            'currency' => [
//                                'id' => (string) $currency->id,
//                                'name' => (string) $currency->name,
//                            ],
//                            'status' => [
//                                'id' => (string) $status->id,
//                                'name' => (string) $status->name,
//                            ],
//                            'payment_urgency' => [
//                                'id' => (string) $paymentUrgency->id,
//                                'name' => (string) $paymentUrgency->name,
//                            ],
//                            'payment_operation_type' => [
//                                'id' => (string) $paymentOperationType->id,
//                                'name' => (string) $paymentOperationType->name,
//                            ],
//                            'payment_provider' => [
//                                'id' => (string) $paymentProvider->id,
//                                'name' => (string) $paymentProvider->name,
//                                'description' => (string) $paymentProvider->description,
//                            ],
//                            'payment_provider_history' => [
//                                'id' => (string) $paymentProviderHistory->id,
//                                'payment_provider_id' => (string) $paymentProviderHistory->payment_provider_id,
//                                'transfer_id' => (string) $paymentProviderHistory->transfer_id,
//                            ],
//                            'payment_system' => [
//                                'id' => (string) $paymentSystem->id,
//                                'name' => (string) $paymentSystem->name,
//                            ],
//                            'payment_bank' => [
//                                'id' => (string) $paymentBank->id,
//                                'name' => (string) $paymentBank->name,
//                                'address' => (string) $paymentBank->address,
//                            ],
//                            'payment_number' => $transferOutgoing->payment_number,
//                            'transfer_type' => [
//                                'id' => (string) $transferType->id,
//                                'name' => (string) $transferType->name,
//                            ],
//                            'account' => [
//                                'id' => (string) $account->id,
//                                'account_type' => (string) $account->account_type,
//                            ],
//                            'company' => [
//                                'id' => (string) $company->id,
//                                'name' => (string) $company->name,
//                                'email' => (string) $company->email,
//                            ],
//                            'system_message' => $transferOutgoing->system_message,
//                            'reason' => $transferOutgoing->reason,
//                            'channel' => $transferOutgoing->channel,
//                            'bank_message' => $transferOutgoing->bank_message,
//                            'recipient_account' => $transferOutgoing->recipient_account,
//                            'recipient_bank_name' => $transferOutgoing->recipient_bank_name,
//                            'recipient_bank_address' => $transferOutgoing->recipient_bank_address,
//                            'recipient_bank_swift' => $transferOutgoing->recipient_bank_swift,
//                            'recipient_bank_country' => [
//                                'id' => (string) $recipientBankCountry->id,
//                                'name' => (string) $recipientBankCountry->name,
//                            ],
//                            'recipient_name' => $transferOutgoing->recipient_name,
//                            'recipient_country' => [
//                                'id' => (string) $recipientCountry->id,
//                                'name' => (string) $recipientCountry->name,
//                            ],
//                            'recipient_city' => $transferOutgoing->recipient_city,
//                            'recipient_address' => $transferOutgoing->recipient_address,
//                            'recipient_state' => $transferOutgoing->recipient_state,
//                            'recipient_zip' => $transferOutgoing->recipient_zip,
//                            'respondent_fee' => [
//                                'id' => (string) $respondentFee->id,
//                                'name' => (string) $respondentFee->name,
//                            ],
//                            'transfer_swift' => [
//                                'swift' => (string) $transferSwift->swift,
//                                'bank_name' => (string) $transferSwift->bank_name,
//                                'bank_address' => (string) $transferSwift->bank_address,
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ]);
//    }

    public function testQueryTransferIncomingStatistic(): void
    {
        $statistics = TransferOutgoing::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
        ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferOutgoingsStatistic {
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
        $statistics = TransferOutgoing::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferOutgoingsStatistic (company_id: 1) {
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
        $statistics = TransferOutgoing::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferOutgoingsStatistic (payment_provider_id: 1) {
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
        $statistics = TransferOutgoing::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name'])
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    transferOutgoingsStatistic (account_id: 1) {
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
