<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class EmailTemplatesQueryTest extends TestCase
{
    /**
     * Email Templates Query Testing
     *
     * @return void
     */

    public function testQueryEmailTemplateById(): void
    {
        $this->login();

        $email_template = DB::connection('pgsql_test')->table('email_templates')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
            query EmailTemplate($id: ID!) {
                emailTemplate(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($email_template[0]->id),
        ])->seeJson([
            'data' => [
                'emailTemplate' => [
                    'id' => strval($email_template[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsByCompany(): void
    {
        $this->login();

        $email_template = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
            query {
                emailTemplates(where: { column: COMPANY_ID, value: 1 }) {
                    id
                }
            }
            ')->seeJsonContains([
            [
                'id' => strval($email_template[0]->id),
            ],
        ]);
    }
}
