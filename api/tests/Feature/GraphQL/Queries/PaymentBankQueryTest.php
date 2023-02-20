<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\PaymentBank;
use App\Models\Payments;
use Tests\TestCase;

class PaymentBankQueryTest extends TestCase
{
    public function testQueryPaymentBanksNoAuth(): void
    {
        $this->graphQL('
            {
                paymentBanks {
                    data {
                        id
                        name
                        address
                        bank_code
                        payment_system_code
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentBank(): void
    {
        $paymentBank = PaymentBank::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentBank($id: ID) {
                    paymentBank(id: $id) {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                    }
                }',
                'variables' => [
                    'id' => $paymentBank->id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentBank' => [
                        'id' => (string) $paymentBank->id,
                        'name' => (string) $paymentBank->name,
                        'address' => (string) $paymentBank->address,
                        'bank_code' => (string) $paymentBank->bank_code,
                        'payment_system_code' => (string) $paymentBank->payment_system_code,
                    ],
                ],
        ]);
    }

    public function testQueryPaymentBanksList(): void
    {
        $paymentBanks = PaymentBank::get();

        foreach ($paymentBanks as $paymentBank) {
            $data[] = [
                'id' => (string) $paymentBank->id,
                'name' => (string) $paymentBank->name,
                'address' => (string) $paymentBank->address,
                'bank_code' => (string) $paymentBank->bank_code,
                'payment_system_code' => (string) $paymentBank->payment_system_code,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    paymentBanks {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentBanks' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryPaymentBanksWithFilterByName(): void
    {
        $paymentBanks = PaymentBank::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $paymentBanks->id,
            'name' => (string) $paymentBanks->name,
            'address' => (string) $paymentBanks->address,
            'bank_code' => (string) $paymentBanks->bank_code,
            'payment_system_code' => (string) $paymentBanks->payment_system_code,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PaymentBanks($name: Mixed) {
                    paymentBanks (
                        filter: { column: NAME, operator: ILIKE, value: $name }
                    ) {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $paymentBanks->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryPaymentBanksWithFilterByAddress(): void
    {
        $paymentBanks = PaymentBank::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $paymentBanks->id,
            'name' => (string) $paymentBanks->name,
            'address' => (string) $paymentBanks->address,
            'bank_code' => (string) $paymentBanks->bank_code,
            'payment_system_code' => (string) $paymentBanks->payment_system_code,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PaymentBanks($address: Mixed) {
                    paymentBanks (
                        filter: { column: ADDRESS, operator: ILIKE, value: $address }
                    ) {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
                'variables' => [
                    'address' => (string) $paymentBanks->address,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryPaymentBanksWithFilterByBankCode(): void
    {
        $paymentBanks = PaymentBank::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $paymentBanks->id,
            'name' => (string) $paymentBanks->name,
            'address' => (string) $paymentBanks->address,
            'bank_code' => (string) $paymentBanks->bank_code,
            'payment_system_code' => (string) $paymentBanks->payment_system_code,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PaymentBanks($bank_code: Mixed) {
                    paymentBanks (
                        filter: { column: BANK_CODE, operator: ILIKE, value: $bank_code }
                    ) {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
                'variables' => [
                    'bank_code' => (string) $paymentBanks->bank_code,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryPaymentBanksWithFilterByPaymentSystemCode(): void
    {
        $paymentBanks = PaymentBank::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $paymentBanks->id,
            'name' => (string) $paymentBanks->name,
            'address' => (string) $paymentBanks->address,
            'bank_code' => (string) $paymentBanks->bank_code,
            'payment_system_code' => (string) $paymentBanks->payment_system_code,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PaymentBanks($payment_system_code: Mixed) {
                    paymentBanks (
                        filter: { column: PAYMENT_SYSTEM_CODE, operator: ILIKE, value: $payment_system_code }
                    ) {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
                'variables' => [
                    'payment_system_code' => (string) $paymentBanks->payment_system_code,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    /**
     * @dataProvider provide_testQueryPaymentBanksWithFilterByCondition
     */
    public function testQueryPaymentBanksWithFilterByCondition($cond, $value): void
    {
        $paymentBanks = PaymentBank::where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $data = [
            'data' => [
                'paymentBanks' => [],
            ],
        ];

        foreach ($paymentBanks as $paymentBank) {
            $data['data']['paymentBanks']['data'][] = [
                'id' => (string) $paymentBank->id,
                'name' => (string) $paymentBank->name,
                'address' => (string) $paymentBank->address,
                'bank_code' => (string) $paymentBank->bank_code,
                'payment_system_code' => (string) $paymentBank->payment_system_code,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query PaymentBanks($id: Mixed) {
                    paymentBanks (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            name
                            address
                            bank_code
                            payment_system_code
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function provide_testQueryPaymentBanksWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['country_id', '1'],
            ['payment_provider_id', '1'],
            ['payment_system_id', '1'],
        ];
    }

    /*public function testQueryUsersWithFilterByFullname(): void
    {
        $users = Users::orderBy('id', 'ASC')
            ->first();

        $expect = [
            'id' => (string) $users->id,
            'email' => (string) $users->email,
        ];

        $this->postGraphQL(
            [
                'query' => 'query Users($name: Mixed) {
                    users (
                        filter: { column: FULLNAME, operator: ILIKE, value: $name }
                    ) {
                        data {
                            id
                            email
                        }
                    }
                }',
                'variables' => [
                    'name' => $users->fullname,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function testQueryUsersWithFilterByEmail(): void
    {
        $users = Users::orderBy('id', 'ASC')
            ->first();

        $expect = [
            'id' => (string) $users->id,
            'email' => (string) $users->email,
        ];

        $this->postGraphQL(
            [
                'query' => 'query Users($email: Mixed) {
                    users (
                        filter: { column: EMAIL, operator: ILIKE, value: $email }
                    ) {
                        data {
                            id
                            email
                        }
                    }
                }',
                'variables' => [
                    'email' => $users->email,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($expect);
    }*/

    /**
     * @dataProvider provide_testQueryUsersWithFilterByCondition
     */
    /*public function testQueryUsersWithFilterByCondition($cond, $value): void
    {
        $users = Users::where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $expect = [
            'data' => [
                'users' => [],
            ],
        ];

        foreach ($users as $user) {
            $expect['data']['users']['data'][] = [
                'id' => (string) $user->id,
                'email' => (string) $user->email,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query Users($id: Mixed) {
                    users (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            email
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function provide_testQueryUsersWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['company_id', '1'],
            ['group_id', '1'],
            ['group_type_id', '1'],
            ['role_id', '2'],
        ];
    }*/
}
