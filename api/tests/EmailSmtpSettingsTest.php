<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class EmailSmtpSettingsTest extends TestCase
{
    /**
     * Email SMTP Settings Testing
     *
     * @return void
     */
    public function testCreateEmailSmtpSettings()
    {
        $this->login();
        $seq = DB::table('email_smtps')->max('id') + 1;
        DB::select('ALTER SEQUENCE email_smtps_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreateEmailSmtp(
                      $name: String!
                      $host_name: String!
                      $from_name: String
                      $from_email: String
                      $username: String!
                      $password: String!
                      $replay_to: String
                      $company_id: ID!
                ) {
                createEmailSmtp(
                    name: $name
                    security: Ssl
                    host_name: $host_name
                    from_name: $from_name
                    from_email: $from_email
                    username: $username
                    password: $password
                    replay_to: $replay_to
                    port: 465
                    company_id: $company_id
                    is_sending_mail: true
                )
              {
                 id
              }
           }
        ', [
            'name' => 'Test Email Smtp',
            'host_name' =>  'mail.lavachange.com',
            'from_name' => 'Docutestststs',
            'from_email' => 'test@lavachange.com',
            'username' => 'test@lavachange.com',
            'password' => 'test@test@123',
            'replay_to' => 'test@lavachange.com',
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createEmailSmtp' => [
                   'id' => $id['data']['createEmailSmtp']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateEmailSmtpSettings()
    {
        $this->login();
        $smtp_settings = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdateEmailSmtp(
                  $id: ID!
                  $host_name: String!
                  $from_name: String
                  $from_email: String
                  $username: String!
                  $password: String!
                  $replay_to: String
            )
            {
                updateEmailSmtp (
                    id: $id
                    security: Ssl
                    host_name: $host_name
                    from_name: $from_name
                    from_email: $from_email
                    username: $username
                    password: $password
                    replay_to: $replay_to
                    port: 465
                )
                {
                    id
                    username
                }
            }
        ', [
            'id' => strval($smtp_settings[0]->id),
            'host_name' =>  'mail.lavachange.com',
            'from_name' => 'Docutest_updated',
            'from_email' => 'test@lavachange.com',
            'username' => 'test@lavachange.com',
            'password' => 'test@test@123',
            'replay_to' => 'test@lavachange.com',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateEmailSmtp' => [
                    'id' => $id['data']['updateEmailSmtp']['id'],
                    'username' => $id['data']['updateEmailSmtp']['username'],
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsById()
    {
        $this->login();
        $smtp_settings = DB::connection('pgsql_test')->table('email_smtps')->orderBy('id', 'DESC')->take(1)->get();
        ($this->graphQL('
            query EmailSmtp($id: ID!) {
                emailSmtp(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($smtp_settings[0]->id),
        ]))->seeJson([
            'data' => [
                'emailSmtp' => [
                    'id' => strval($smtp_settings[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailSmtpSettingsByCompany()
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

    public function testSendEmail()
    {
        $this->login();
        $this->graphQL('
            mutation SendEmail(
                      $host_name: String!
                      $from_name: String
                      $from_email: String
                      $username: String!
                      $password: String!
                      $replay_to: String
                      $email: String!
                ) {
                sendEmail(
                    security: Ssl
                    host_name: $host_name
                    from_name: $from_name
                    from_email: $from_email
                    username: $username
                    password: $password
                    replay_to: $replay_to
                    port: 465
                    email: $email
                )
              {
                 status
              }
           }
        ', [
            'host_name' =>  'mail.lavachange.com',
            'from_name' => 'Docutestststs',
            'from_email' => 'test@lavachange.com',
            'username' => 'test@lavachange.com',
            'password' => 'test@test@123',
            'email' => 'test@lavachange.com',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'sendEmail' => [
                    'status' => $id['data']['sendEmail']['status'],
                ],
            ],
        ]);
    }

}
