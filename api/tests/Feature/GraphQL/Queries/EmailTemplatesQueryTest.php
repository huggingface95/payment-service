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

        $email_template = DB::connection('pgsql_test')
            ->table('email_templates')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

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

    public function testQueryEmailTemplatesByName(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->graphQL('
            query EmailTemplates($name: Mixed) {
                emailTemplates(
                    filter: {
                        column: NAME
                        operator: ILIKE
                        value: $name
                    }
                ) {
                    id
                    name
                    subject
                    type
                }
            }
        ', [
            'name' => $email->name
        ])->seeJsonContains([
            'id' => strval($email->id),
            'name' => strval(ucfirst($email->name)),
            'subject' => strval($email->subject),
            'type' => strval(ucfirst($email->type)),
        ]);
    }

    public function testQueryEmailTemplatesByCompanyId(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->graphQL('
            query EmailTemplates($id: Mixed) {
                emailTemplates(
                    filter: {
                        column: COMPANY_ID
                        value: $id
                    }
                ) {
                    id
                    name
                    subject
                    type
                }
            }
        ', [
            'id' => $email->company_id
        ])->seeJsonContains([
            'id' => strval($email->id),
            'name' => strval(ucfirst($email->name)),
            'subject' => strval($email->subject),
            'type' => strval(ucfirst($email->type)),
        ]);
    }

    public function testQueryEmailTemplatesByType(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->graphQL('
            query EmailTemplates($type: Mixed) {
                emailTemplates(
                    filter: {
                        column: TYPE
                        value: $type
                    }
                ) {
                    id
                    name
                    subject
                    type
                }
            }
        ', [
            'type' => $email->type
        ])->seeJsonContains([
            'id' => strval($email->id),
            'name' => strval(ucfirst($email->name)),
            'subject' => strval($email->subject),
            'type' => strval(ucfirst($email->type)),
        ]);
    }

    public function testQueryEmailTemplatesByServiceType(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->graphQL('
            query EmailTemplates($type: Mixed) {
                emailTemplates(
                    filter: {
                        column: SERVICE_TYPE
                        value: $type
                    }
                ) {
                    id
                    name
                    subject
                    type
                }
            }
        ', [
            'type' => $email->service_type
        ])->seeJsonContains([
            'id' => strval($email->id),
            'name' => strval(ucfirst($email->name)),
            'subject' => strval($email->subject),
            'type' => strval(ucfirst($email->type)),
        ]);
    }
}
