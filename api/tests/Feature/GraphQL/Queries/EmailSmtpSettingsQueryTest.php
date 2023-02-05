<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class EmailSmtpSettingsQueryTest extends TestCase
{
    /**
     * Email SMTP Settings Query Testing
     *
     * @return void
     */

    public function testEmailSmtpSettingsNoAuth(): void
    {
        $this->graphQL('
             {
                emailSmtps (company_id: 1)
                 {
                    id
                 }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryEmailSmtpSettingsById(): void
    {
        $smtp_settings = DB::connection('pgsql_test')
            ->table('email_smtps')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query EmailSmtp($id: ID!) {
                    emailSmtp(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $smtp_settings[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'emailSmtp' => [
                    'id' => (string) $smtp_settings[0]->id,
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsByCompany(): void
    {
        $smtp_settings = DB::connection('pgsql_test')
            ->table('email_smtps')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query EmailSmtps($company_id: ID!) {
                    emailSmtps(company_id: $company_id) {
                        id
                    }
                }',
            'variables' => [
                'company_id' => $smtp_settings[0]->company_id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $smtp_settings[0]->id,
            ],
        ]);
    }
}
