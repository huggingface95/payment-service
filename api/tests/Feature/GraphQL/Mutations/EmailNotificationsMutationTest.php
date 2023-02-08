<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class EmailNotificationsMutationTest extends TestCase
{
    /**
     * Email Notifications Mutation Testing
     *
     * @return void
     */
    public function testCreateEmailNotificationNoAuth(): void
    {
        $seq = DB::table('email_notifications')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE email_notifications_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateEmailNotification(
                      $group_type_id: ID!
                      $group_role_id: ID!
                      $company_id: ID!
                ) {
                createEmailNotification(
                    group_type_id: $group_type_id
                    group_role_id: $group_role_id
                    templates: 1
                    company_id: $company_id
                )
              {
                 id
              }
           }
        ', [
            'group_type_id' => 2,
            'group_role_id' =>  1,
            'company_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateEmailNotification(): void
    {
        $seq = DB::table('email_notifications')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE email_notifications_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateEmailNotification(
                          $group_type_id: ID!
                          $group_role_id: ID!
                          $company_id: ID!
                    ) {
                    createEmailNotification(
                        group_type_id: $group_type_id
                        group_role_id: $group_role_id
                        templates: 1
                        company_id: $company_id
                    )
                  {
                     id
                  }
               }',
                'variables' => [
                    'group_type_id' => 2,
                    'group_role_id' =>  1,
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createEmailNotification' => [
                    'id' => $id['data']['createEmailNotification']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateEmailNotification(): void
    {
        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateEmailNotification(
                      $id: ID!
                      $group_type_id: ID!
                      $group_role_id: ID!
                      $company_id: ID!
                )
                {
                    updateEmailNotification(
                        id: $id
                        group_type_id: $group_type_id
                        group_role_id: $group_role_id
                        templates: 1
                        company_id: $company_id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $email_notification[0]->id,
                    'group_type_id' => 2,
                    'group_role_id' =>  1,
                    'company_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateEmailNotification' => [
                    'id' => $id['data']['updateEmailNotification']['id'],
                ],
            ],
        ]);
    }
}
