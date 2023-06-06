<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferBetween;
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
                'Authorization' => 'Bearer '.$this->login(),
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
        $transfer = TransferBetween::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation SignTransferBetweenAccounts($id: ID!, $code: String!) {
                      signTransferBetweenAccounts(
                        id: $id
                        code: $code
                      ) {
                        id
                      }
                    }
                ',
                'variables' => [
                    'id' => $transfer->id,
                    'code' => '658999',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'signTransferBetweenAccounts' => [
                    'id' => $id['data']['signTransferBetweenAccounts']['id'],
                ],
            ],
        ]);
    }

    public function testExecuteTransferBetweenAccounts(): void
    {
        $transfer = TransferBetween::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation ExecuteTransferBetweenAccounts($transfer: ID!) {
                      executeTransferBetweenAccounts(
                        id: $transfer
                      ) {
                        id
                      }
                    }
                ',
                'variables' => [
                    'transfer' => $transfer->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'executeTransferBetweenAccounts' => [
                    'id' => $id['data']['executeTransferBetweenAccounts']['id'],
                ],
            ],
        ]);
    }
}
