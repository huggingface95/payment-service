<?php

namespace Tests;

use App\DTO\Email\SmtpDataDTO;
use App\Mail\SomeMailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailTemplatesMutationTest extends TestCase
{
    /**
     * Email Templates Mutation Testing
     *
     * @return void
     */
    public function testCreateEmailTemplateNoAuth(): void
    {
        $seq = DB::table('email_templates')
                ->max('id') + 1;

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
                    service_type: Common
                    type: Client
                    company_id: $company_id
                )
              {
                 data {
                    id
                 }
              }
           }
        ', [
            'name' => 'Test Email Template mutator test',
            'subject' =>  'Waiting for approval',
            'content' => '<html></html>',
            'company_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    //TODO Find out why not working loginAsSuperAdmin
    /*public function testCreateEmailTemplate(): void
    {
        $seq = DB::table('email_templates')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE email_templates_id_seq RESTART WITH '.$seq);

        $this->postGraphQL([
            'query' => '
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
                        service_type: Common
                        type: Client
                        company_id: $company_id
                    )
                  {
                     data {
                        id
                     }
                  }
               }',
            'variables' => [
                'name' => 'Test Email Template mutator test',
                'subject' =>  'Waiting for approval',
                'content' => '<html></html>',
                'company_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createEmailTemplate' => [
                    'data' => $id['data']['createEmailTemplate']['data'],
                ],
            ],
        ]);
    }*/

    public function testUpdateEmailTemplate(): void
    {
        $email_template = DB::connection('pgsql_test')
            ->table('email_templates')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => strval($email_template[0]->id),
                    'subject' =>  'updated_subject',
                    'content' => '<html></html>',
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateEmailTemplate' => [
                    'data' => $id['data']['updateEmailTemplate']['data'],
                ],
            ],
        ]);
    }

    public function testDeleteEmailTemplate(): void
    {
        $email_notification = DB::connection('pgsql_test')
            ->table('email_templates')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteEmailTemplate(
                    $id: ID!
                )
                {
                    deleteEmailTemplate (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $email_notification[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteEmailTemplate' => [
                    'id' => $id['data']['deleteEmailTemplate']['id'],
                ],
            ],
        ]);
    }

    public function testSendEmail(): void
    {
        Mail::fake();
        Mail::to('test@lavachange.com')->send(new SomeMailable(SmtpDataDTO::transform('test@lavachange.com', '<html><body>test</body></html>', 'test subj')));
        Mail::assertSent(SomeMailable::class, function ($mail) {
            $mail->from('test@lavachange.com');
            $mail->build();

            return $mail->hasFrom('test@lavachange.com') && $mail->subject === 'test subj';
        });
    }
}
