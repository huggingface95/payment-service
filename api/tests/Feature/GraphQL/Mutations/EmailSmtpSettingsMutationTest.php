<?php

namespace Tests;

use App\Mail\SomeMailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\DTO\Email\SmtpDataDTO;

class EmailSmtpSettingsMutationTest extends TestCase
{
    /**
     * Email SMTP Settings Mutation Testing
     *
     * @return void
     */
    public function testCreateEmailSmtpSettings(): void
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

    public function testUpdateEmailSmtpSettings(): void
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

    public function testSendEmail(): void
    {
        $this->login();

        Mail::fake();
        Mail::to('test@lavachange.com')->send(New SomeMailable(SmtpDataDTO::transform('test@lavachange.com', '<html><body>test</body></html>', 'test subj')));
        Mail::assertSent(SomeMailable::class, function($mail) {
            $mail->from('test@lavachange.com');
            $mail->build();

            return $mail->hasFrom('test@lavachange.com') && $mail->subject === 'test subj';
        });
    }
}
