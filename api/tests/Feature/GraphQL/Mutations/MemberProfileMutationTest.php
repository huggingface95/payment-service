<?php

namespace Feature\GraphQL\Mutations;

use Tests\TestCase;

class MemberProfileMutationTest extends TestCase
{
    /**
     * MemberProfile Mutation Testing
     *
     * @return void
     */
    public function testUpdateMemberProfileNoAuth(): void
    {
        $this->graphQL('
            mutation UpdateMemberProfile(
                $first_name: String,
                $last_name: String,
                $country_id: ID,
                $language_id: ID,
            ) {
                updateMemberProfile(
                    first_name: $first_name,
                    last_name: $last_name,
                    country_id: $country_id,
                    language_id: $language_id,
                ) {
                    id
                }
            }
        ', [
            'first_name' => 'Member 2',
            'last_name' => 'Member 2 last',
            'country_id' => 1,
            'language_id' => 1,
        ])->seeJsonContains([
            'message' => 'An entry with this id does not exist',
        ]);
    }

    public function testUpdateMemberProfile(): void
    {
        $this->postGraphQL(['query' => '
            mutation UpdateMemberProfile(
                $first_name: String,
                $last_name: String,
                $country_id: ID,
                $language_id: ID,
            ) {
                updateMemberProfile(
                    first_name: $first_name,
                    last_name: $last_name,
                    country_id: $country_id,
                    language_id: $language_id,
                ) {
                    id
                    first_name
                    last_name
                    sex
                    is_active
                    last_login_at
                    two_factor_auth_setting_id
                    fullname
                    is_show_owner_applicants
                    security_pin
                    google2fa_secret
                    backup_codes
                    is_sign_transaction
                }
            }
        ', 'variables' => [
            'first_name' => 'Member 2',
            'last_name' => 'Member 2 last',
            'country_id' => 1,
            'language_id' => 1,
        ],
        ],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'id' => $response['data']['updateMemberProfile']['id'],
            'first_name' => $response['data']['updateMemberProfile']['first_name'],
            'last_name' => $response['data']['updateMemberProfile']['last_name'],
            'sex' => $response['data']['updateMemberProfile']['sex'],
            'is_active' => $response['data']['updateMemberProfile']['is_active'],
            'last_login_at' => $response['data']['updateMemberProfile']['last_login_at'],
            'two_factor_auth_setting_id' => $response['data']['updateMemberProfile']['two_factor_auth_setting_id'],
            'fullname' => $response['data']['updateMemberProfile']['fullname'],
            'is_show_owner_applicants' => $response['data']['updateMemberProfile']['is_show_owner_applicants'],
            'security_pin' => $response['data']['updateMemberProfile']['security_pin'],
            'google2fa_secret' => $response['data']['updateMemberProfile']['google2fa_secret'],
            'backup_codes' => $response['data']['updateMemberProfile']['backup_codes'],
            'is_sign_transaction' => $response['data']['updateMemberProfile']['is_sign_transaction'],
        ]);
    }

    public function testSendConfirmChangeEmail(): void
    {
        $this->postGraphQL(['query' => '
            mutation SendConfirmChangeEmail(
                $email: String!
            ) {
                sendConfirmChangeEmail(
                    email: $email
                ) {
                    status
                    message
                }
            }
        ', 'variables' => [
            'email' => 'test_mail@test.com',
        ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'status' => $response['data']['sendConfirmChangeEmail']['status'],
            'message' => $response['data']['sendConfirmChangeEmail']['message'],
        ]);
    }
}
