<?php

namespace Feature\GraphQL\Mutations;

use App\Enums\PaymentStatusEnum;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferExchangeMutationTest extends TestCase
{
    /**
     * TransferIncomings Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferExchangeNoAuth(): void
    {
        $this->graphQL('
            mutation createTransferExchange($from_account: ID!, $to_account: ID!) {
                  createTransferExchange(
                    amount: 10
                    from_account_id: $from_account
                    to_account_id: $to_account
                    price_list_fee_id: 1
                  )
                {
                  id
                }
            }
        ', [
            'from_account' => 1,
            'to_account' => 2,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferExchangeWithStatusUnsigned(): void
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
                    mutation CreateTransferExchange($from_account: ID!, $to_account: ID!) {
                      createTransferExchange(
                        amount: 10
                        from_account_id: $from_account
                        to_account_id: $to_account
                        price_list_fee_id: 1
                      ) {
                        id
                        status{
                            id
                        }
                    }
                }
                ',
                'variables' => [
                    'from_account' => 5,
                    'to_account' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferExchange' => [
                    'id' => $id['data']['createTransferExchange']['id'],
                    'status' => [
                        'id' => (string) PaymentStatusEnum::UNSIGNED->value,
                    ],
                ],
            ],
        ]);
    }
}
