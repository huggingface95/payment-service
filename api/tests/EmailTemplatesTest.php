<?php

use Illuminate\Support\Facades\DB;

class EmailTemplatesTest extends TestCase
{
    /**
     * Email Templates Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateEmailTemplate()
    {
        $this->login();
        $seq = DB::table('email_templates')->max('id') + 1;
        DB::select('ALTER SEQUENCE email_templates_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreateEmailTemplate(
                      $name: String!
                      $subject: String!
                      $content: String!
                      $company_id: ID!
                ) {
                createEmailTemplate(
                    name: $name
                    subject: $subject
                    content: $content
                    use_layout: false
                    service_type: Banking
                    type: Administration
                    company_id: $company_id
                )
              {
                 data {
                    id
                 }
              }
           }
        ', [
            'name' => 'Test Email Template',
            'subject' =>  'Waiting for approval',
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createEmailTemplate' => [
                   'data' => $id['data']['createEmailTemplate']['data'],
                ],
            ],
        ]);
    }

    public function testUpdateEmailSmtpSettings()
    {
        $this->login();
        $email_template = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdateEmailTemplate(
                  $id: ID!
                  $subject: String!
                  $content: String!
                  $company_id: ID!
            )
            {
                updateEmailTemplate (
                    id: $id
                    subject: $subject
                    content: $content
                    use_layout: false
                    service_type: Banking
                    type: Administration
                    company_id: $company_id
                )
                {
                    data {
                        id
                    }
                }
            }
        ', [
            'id' => strval($email_template[0]->id),
            'subject' =>  'updated_subject',
            'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateEmailTemplate' => [
                    'data' => $id['data']['updateEmailTemplate']['data'],
                ],
            ],
        ]);
    }

    public function testQueryEmailTemplateById()
    {
        $this->login();
        $email_template = DB::connection('pgsql_test')->table('email_templates')->orderBy('id', 'DESC')->take(1)->get();
        ($this->graphQL('
            query EmailTemplate($id: ID!) {
                emailTemplate(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($email_template[0]->id),
        ]))->seeJson([
            'data' => [
                'emailTemplate' => [
                    'id' => strval($email_template[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsByCompany()
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

    public function testSendEmailWithTemplate()
    {
        $this->login();
        $this->graphQL('
            mutation SendEmailWithTemplate(
                      $email: String!
                      $company_id: ID!
                      $subject: String!
                ) {
                sendEmailWithTemplate(
                    company_id: $company_id
                    email: $email
                    subject: $subject
                )
              {
                 status
              }
           }
        ', [
            'email' => 'test@lavachange.com',
            'company_id' => 1,
            'subject' => 'SendEmailWithTemplate',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'sendEmailWithTemplate' => [
                    'status' => $id['data']['sendEmailWithTemplate']['status'],
                ],
            ],
        ]);
    }

}
