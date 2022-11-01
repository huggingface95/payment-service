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

        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

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
            'group_role_id' =>  3,
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

        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            query {
                emailNotifications(hasGroupRole: { column: ID, value: 3 }) {
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

    public function testQueryEmailNotificationsByCompanyId(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->graphQL('
            query EmailNotifications($id: Mixed) {
                emailNotifications(
                    filter: { column: COMPANY_ID, value: $id }
                ) {
                    data {
                        id
                        type
                        recipient_type
                    }
                }
            }
        ', [
            'id' => $email->company_id
        ])->seeJsonContains([
            'id' => strval($email->id),
            'type' => strval(ucfirst($email->type)),
            'recipient_type' => strval(strtoupper($email->recipient_type)),
        ]);
    }

    public function testQueryEmailNotificationsByType(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->graphQL('
            query EmailNotifications($type: Mixed) {
                emailNotifications(
                    filter: { column: TYPE, value: $type }
                ) {
                    data {
                    id
                    type
                    recipient_type
                    }
                }
            }
        ', [
            'type' => $email->type
        ])->seeJsonContains([
            'id' => strval($email->id),
            'type' => strval(ucfirst($email->type)),
            'recipient_type' => strval(strtoupper($email->recipient_type)),
        ]);
    }

    public function testQueryEmailNotificationsByGroupRole(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->graphQL('
            query EmailNotifications($id: Mixed) {
                emailNotifications(
                    filter: { column: HAS_GROUP_ROLE_MIXED_ID_OR_NAME, value: $id }
                ) {
                    data {
                        id
                        type
                        recipient_type
                    }
                }
            }
        ', [
            'id' => $email->group_role_id
        ])->seeJsonContains([
            'id' => strval($email->id),
            'type' => strval(ucfirst($email->type)),
            'recipient_type' => strval(strtoupper($email->recipient_type)),
        ]);
    }

    public function testQueryEmailNotificationsByGroupType(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->graphQL('
            query EmailNotifications($id: Mixed) {
                emailNotifications(
                    filter: { column: HAS_GROUP_TYPE_MIXED_ID_OR_NAME, value: $id }
                ) {
                    data {
                        id
                        type
                        recipient_type
                    }
                }
            }
        ', [
            'id' => $email->group_type_id
        ])->seeJsonContains([
            'id' => strval($email->id),
            'type' => strval(ucfirst($email->type)),
            'recipient_type' => strval(strtoupper($email->recipient_type)),
        ]);
    }

    public function testQueryEmailNotificationsBySubject(): void
    {
        $this->login();

        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $subject = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->graphQL('
            query EmailNotifications($subject: Mixed) {
                emailNotifications(
                    filter: {
                        column: HAS_TEMPLATES_FILTER_BY_SUBJECT
                        operator: ILIKE
                        value: $subject
                    }
                ) {
                    data {
                        id
                        type
                        recipient_type
                    }
                }
            }
        ', [
            'subject' => $subject->subject
        ])->seeJsonContains([
            'id' => strval($email->id),
            'type' => strval(ucfirst($email->type)),
            'recipient_type' => strval(strtoupper($email->recipient_type)),
        ]);
    }
}
