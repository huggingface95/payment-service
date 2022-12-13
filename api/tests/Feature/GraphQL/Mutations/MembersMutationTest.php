<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class MembersMutationTest extends TestCase
{
    /**
     * Members Mutation Testing
     *
     * @return void
     */

    public function testCreateMember(): void
    {
        $this->login();

        $seq = DB::table('members')->max('id') + 1;
        DB::select('ALTER SEQUENCE members_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateMember(
                $first_name: String!
                $last_name: String!
                $email: EMAIL!
                $company_id: ID!
                $country_id: ID!
                $language_id: ID!
                $group_id: ID!
                $two_factor_auth_setting_id: ID!
                $password: String
            )
            {
                createMember (
                    first_name: $first_name
                    last_name: $last_name
                    email: $email
                    company_id: $company_id
                    country_id: $country_id
                    language_id: $language_id
                    group_id: $group_id
                    two_factor_auth_setting_id: $two_factor_auth_setting_id
                    password: $password
                )
                {
                    id
                }
            }
        ', [
            'first_name' =>  'Member'.str_pad(mt_rand(1, 9), 3, '0', STR_PAD_LEFT),
            'last_name' => 'MemberLast'.str_pad(mt_rand(1, 9), 3, '0', STR_PAD_LEFT),
            'email' => 'test'.str_pad(mt_rand(1, 9), 2, '0', STR_PAD_LEFT).'@test.com',
            'company_id' => 1,
            'country_id' => 1,
            'language_id' => 1,
            'group_id' => 1,
            'two_factor_auth_setting_id' => 1,
            'password' => '1234567Qa',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createMember' => [
                    'id' => $id['data']['createMember']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateMember(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation UpdateMember(
                $id: ID!
                $email: EMAIL!
            )
            {
                updateMember (
                    id: $id
                    email: $email
                )
                {
                    id
                    email
                }
            }
        ', [
            'id' => strval($member[0]->id),
            'email' => 'test'.str_pad(mt_rand(1, 9), 2, '0', STR_PAD_LEFT).'@test.com',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateMember' => [
                    'id' => $id['data']['updateMember']['id'],
                    'email' => $id['data']['updateMember']['email'],
                ],
            ],
        ]);
    }

    public function testSetMemberPassword(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation SetMemberPassword(
                $id: ID!
                $password: String!
                $password_confirmation: String!
            )
            {
                setMemberPassword (
                    id: $id
                    password: $password
                    password_confirmation: $password_confirmation
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($member[0]->id),
            'password' => '1234567Za',
            'password_confirmation' => '1234567Za',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'setMemberPassword' => [
                    'id' => $id['data']['setMemberPassword']['id'],
                ],
            ],
        ]);
    }

    public function testSetMemberSecurityPin(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation SetMemberSecurityPin(
                $id: ID!
            )
            {
                setMemberSecurityPin (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($member[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'setMemberSecurityPin' => [
                    'id' => $id['data']['setMemberSecurityPin']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteMember(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation DeleteMember(
                $id: ID!
            )
            {
                deleteMember (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($member[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteMember' => [
                    'id' => $id['data']['deleteMember']['id'],
                ],
            ],
        ]);
    }
}
