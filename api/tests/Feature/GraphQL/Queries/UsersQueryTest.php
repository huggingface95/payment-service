<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Members;
use App\Models\Users;
use Tests\TestCase;

class UsersQueryTest extends TestCase
{
    public function testQueryUsersNoAuth(): void
    {
        $this->graphQL('
            {
                users {
                    data {
                        id
                        fullname
                        email
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testUserAuthData(): void
    {
        $member = Members::find(2);

        $this->postGraphQL(
            [
                'query' => '
                {
                    userAuthData {
                        data {
                            id
                            fullname
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'userAuthData' => [
                    'data' => [
                        'id' => (string) $member->id,
                        'fullname' => $member->fullname,
                    ],
                ],
            ],
        ]);
    }

    public function testQueryUsersWithFilterByFullname(): void
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
                'Authorization' => 'Bearer '.$this->login(),
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
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }

    /**
     * @dataProvider provide_testQueryUsersWithFilterByCondition
     */
    public function testQueryUsersWithFilterByCondition($cond, $value): void
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
                'Authorization' => 'Bearer '.$this->login(),
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
    }
}
