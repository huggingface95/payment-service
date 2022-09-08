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

    public function testQueryEmailSmtpSettingsById(): void
    {
        $this->login();

        $smtp_settings = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
            query EmailSmtp($id: ID!) {
                emailSmtp(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($smtp_settings[0]->id),
        ])->seeJson([
            'data' => [
                'emailSmtp' => [
                    'id' => strval($smtp_settings[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsByCompany(): void
    {
        $this->login();

        $smtp_settings = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
            query EmailSmtps($company_id: ID!) {
                emailSmtps(company_id: $company_id) {
                    id
                }
            }
            ', [
            'company_id' => 1,
        ])->seeJsonContains([
            [
                'id' => strval($smtp_settings[0]->id),
            ],
        ]);
    }
}
