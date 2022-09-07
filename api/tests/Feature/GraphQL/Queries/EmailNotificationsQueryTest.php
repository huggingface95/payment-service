<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class EmailNotificationsQueryTest extends TestCase
{
    /**
     * Email Notifications Query Testing
     *
     * @return void
     */

    public function testQueryEmailNotificationByCompanyId(): void
    {
        $this->login();

        $email_notification = DB::connection('pgsql_test')->table('email_notifications')->orderBy('id', 'ASC')->take(1)->get();

        $this->graphQL('
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
            'company_id' => 1,
        ])->seeJson([
            'data' => [
                'emailNotification' => [
                    'id' => strval($email_notification[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryEmailNotificationsByGroupRoleId(): void
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
