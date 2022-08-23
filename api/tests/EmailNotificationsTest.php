<?php

use Illuminate\Support\Facades\DB;

class EmailNotificationsTest extends TestCase
{
    /**
     * Email Notifications Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateEmailNotification()
    {
        $this->login();
        $seq = DB::table('email_notifications')->max('id') + 1;
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
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createEmailNotification' => [
                   'id' => $id['data']['createEmailNotification']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateEmailNotification()
    {
        $this->login();
        $email_notification = DB::connection('pgsql_test')->table('email_notifications')->orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
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
            }
        ', [
            'id' => strval($email_notification[0]->id),
            'group_type_id' => 2,
            'group_role_id' =>  1,
            'company_id' => 2,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateEmailNotification' => [
                    'id' => $id['data']['updateEmailNotification']['id'],
                ],
            ],
        ]);
    }

    public function testQueryEmailNotificationByCompanyId()
    {
        $this->login();
        $email_notification = DB::connection('pgsql_test')->table('email_notifications')->orderBy('id', 'DESC')->take(1)->get();
        ($this->graphQL('
            query EmailNotification(
            $company_id: ID!
            $group_type_id: ID!
            $group_role_id: ID!
            ) {
                emailNotification(
                company_id: $company_id
                group_type_id: $group_type_id
                group_role_id: $group_role_id
                ) {
                    id
                }
            }
        ', [
            'group_type_id' => 2,
            'group_role_id' =>  1,
            'company_id' => 2,
        ]))->seeJson([
            'data' => [
                'emailNotification' => [
                    'id' => strval($email_notification[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailNotificationsByGroupRoleId()
    {
        $this->login();
        $email_notification = DB::connection('pgsql_test')->table('email_notifications')->orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            query {
                emailNotifications(hasGroupRole: { column: ID, value: 1 }) {
                    data {
                        id
                    }
                }
            }
            ')->seeJsonContains([
            [
                'id' => strval($email_notification[0]->id),
            ],
        ]);
    }

}
