<?php

namespace Tests\Feature\GraphQL\Queries;

use Tests\TestCase;

class MemberProfileQueryTest extends TestCase
{
    public function testQueryMemberProfileNoAuth(): void
    {
        $this->graphQL('
            {
                memberProfile {
                    id
                    first_name
                    last_name
                    email
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
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryMemberProfile(): void
    {
        $this->postGraphQL(['query' => '
            {
                memberProfile {
                    id
                    first_name
                    last_name
                    email
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
        '],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'id' => $response['data']['memberProfile']['id'],
            'first_name' => $response['data']['memberProfile']['first_name'],
            'last_name' => $response['data']['memberProfile']['last_name'],
            'email' => $response['data']['memberProfile']['email'],
            'sex' => $response['data']['memberProfile']['sex'],
            'is_active' => $response['data']['memberProfile']['is_active'],
            'last_login_at' => $response['data']['memberProfile']['last_login_at'],
            'two_factor_auth_setting_id' => $response['data']['memberProfile']['two_factor_auth_setting_id'],
            'fullname' => $response['data']['memberProfile']['fullname'],
            'is_show_owner_applicants' => $response['data']['memberProfile']['is_show_owner_applicants'],
            'security_pin' => $response['data']['memberProfile']['security_pin'],
            'google2fa_secret' => $response['data']['memberProfile']['google2fa_secret'],
            'backup_codes' => $response['data']['memberProfile']['backup_codes'],
            'is_sign_transaction' => $response['data']['memberProfile']['is_sign_transaction'],
        ]);
    }
}
