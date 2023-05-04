<?php

namespace Feature\GraphQL\Mutations;

use App\Models\Account;
use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferBetweenAccountsMutationTest extends TestCase
{
    /**
     * Transfer Between Accounts Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferBetweenAccountsNoAuth(): void
    {
        $this->graphQL('
            mutation createTransferBetweenAccounts($from_account: ID!, $to_account: ID!, $price_list_fee_id: ID!, $price_list_id: ID!) {
                  createTransferBetweenAccounts(
                    amount: 10
                    from_account_id: $from_account
                    to_account_id: $to_account
                    price_list_fee_id: $price_list_fee_id
                    price_list_id: $price_list_id
                  )
                {
                    fee_amount
                    final_amount
                }
            }
        ', [
                'from_account' => 1,
                'to_account' => 2,
                'price_list_fee_id' => 1,
                'price_list_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferBetweenAccounts(): void
    {
        $seq = DB::table('transfer_outgoings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_outgoings_id_seq RESTART WITH '.$seq);

        $seq = DB::table('transfer_incomings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_incomings_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                    mutation CreateTransferBetweenAccounts($from_account: ID!, $to_account: ID!, $price_list_fee_id: ID!, $price_list_id: ID!) {
                      createTransferBetweenAccounts(
                        amount: 10
                        from_account_id: $from_account
                        to_account_id: $to_account
                        price_list_fee_id: $price_list_fee_id
                        price_list_id: $price_list_id
                      ) {
                        transfer_outgoing {
                            id
                            amount
                        }
                        transfer_incoming {
                            id
                            amount
                        }
                        fee_amount
                        final_amount
                      }
                    }
                ',
                    'variables' => [
                        'from_account' => 1,
                        'to_account' => 2,
                        'price_list_fee_id' => 1,
                        'price_list_id' => 1,
                    ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $response = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferBetweenAccounts' => [
                    'transfer_outgoing' => [
                        'id' => $response['data']['createTransferBetweenAccounts']['transfer_outgoing']['id'],
                        'amount' => $response['data']['createTransferBetweenAccounts']['transfer_outgoing']['amount'],
                    ],
                    'transfer_incoming' => [
                        'id' => $response['data']['createTransferBetweenAccounts']['transfer_incoming']['id'],
                        'amount' => $response['data']['createTransferBetweenAccounts']['transfer_incoming']['amount'],
                    ],
                    'fee_amount' => $response['data']['createTransferBetweenAccounts']['fee_amount'],
                    'final_amount' => $response['data']['createTransferBetweenAccounts']['final_amount'],
                ],
            ],
        ]);
    }

    public function testSignTransferBetweenAccounts(): void
    {
        $transfer = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation SignTransferBetweenAccounts($transfer: ID!, $code: String!) {
                      signTransferBetweenAccounts(
                        transfer_incoming_id: $transfer
                        code: $code
                      ) {
                        id
                        amount
                        amount_debt
                        payment_number
                        system_message
                        reason
                        channel
                        bank_message
                      }
                    }
                ',
                'variables' => [
                    'transfer' => $transfer->id,
                    'code' => "658999",
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'signTransferBetweenAccounts' => [
                    'id' => $id['data']['signTransferBetweenAccounts']['id'],
                    'amount' => $id['data']['signTransferBetweenAccounts']['amount'],
                    'amount_debt' => $id['data']['signTransferBetweenAccounts']['amount_debt'],
                    'payment_number' => $id['data']['signTransferBetweenAccounts']['payment_number'],
                    'system_message' => $id['data']['signTransferBetweenAccounts']['system_message'],
                    'reason' => $id['data']['signTransferBetweenAccounts']['reason'],
                    'channel' => $id['data']['signTransferBetweenAccounts']['channel'],
                    'bank_message' => $id['data']['signTransferBetweenAccounts']['bank_message'],
                ],
            ],
        ]);
    }

    public function testExecuteTransferBetweenAccounts(): void
    {
        $transfer = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation ExecuteTransferBetweenAccounts($transfer: ID!) {
                      executeTransferBetweenAccounts(
                        transfer_incoming_id: $transfer
                      ) {
                        id
                        amount
                        amount_debt
                        payment_number
                        system_message
                        reason
                        channel
                        bank_message
                      }
                    }
                ',
                'variables' => [
                    'transfer' => $transfer->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'executeTransferBetweenAccounts' => [
                    'id' => $id['data']['executeTransferBetweenAccounts']['id'],
                    'amount' => $id['data']['executeTransferBetweenAccounts']['amount'],
                    'amount_debt' => $id['data']['executeTransferBetweenAccounts']['amount_debt'],
                    'payment_number' => $id['data']['executeTransferBetweenAccounts']['payment_number'],
                    'system_message' => $id['data']['executeTransferBetweenAccounts']['system_message'],
                    'reason' => $id['data']['executeTransferBetweenAccounts']['reason'],
                    'channel' => $id['data']['executeTransferBetweenAccounts']['channel'],
                    'bank_message' => $id['data']['executeTransferBetweenAccounts']['bank_message'],
                ],
            ],
        ]);
    }
}
