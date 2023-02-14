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
    public function testEmailTemplatesNoAuth(): void
    {
        $this->graphQL('
             {
                emailTemplates
                 {
                    id
                    name
                    subject
                    type
                 }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryEmailTemplate(): void
    {
        $email_template = DB::connection('pgsql_test')
            ->table('email_templates')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query EmailTemplate($id: ID!) {
                    emailTemplate(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $email_template[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'emailTemplate' => [
                    'id' => (string) $email_template[0]->id,
                ],
            ],
        ]);
    }

    public function testQueryEmailTemplatesByName(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'name' => $email->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'name' => (string) ucfirst($email->name),
            'subject' => (string) $email->subject,
            'type' => (string) ucfirst($email->type),
        ]);
    }

    public function testQueryEmailTemplatesByCompanyId(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => $email->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'name' => (string) ucfirst($email->name),
            'subject' => (string) $email->subject,
            'type' => (string) ucfirst($email->type),
        ]);
    }

    public function testQueryEmailTemplatesByType(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'type' => $email->type,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'name' => (string) ucfirst($email->name),
            'subject' => (string) $email->subject,
            'type' => (string) ucfirst($email->type),
        ]);
    }

    public function testQueryEmailTemplatesByServiceType(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'type' => $email->service_type,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'name' => (string) ucfirst($email->name),
            'subject' => (string) $email->subject,
            'type' => (string) ucfirst($email->type),
        ]);
    }

    public function testQueryEmailTemplateByCompanyName(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->where('id', $email->company_id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query EmailTemplates($company: Mixed) {
                    emailTemplates(
                        filter: {
                            column: HAS_COMPANY_FILTER_BY_NAME
                            operator: ILIKE
                            value: $company
                        }
                    ) {
                        id
                        name
                        subject
                        type
                    }
                }',
                'variables' => [
                    'company' => $company->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'name' => (string) ucfirst($email->name),
            'subject' => (string) $email->subject,
            'type' => (string) ucfirst($email->type),
        ]);
    }
}
