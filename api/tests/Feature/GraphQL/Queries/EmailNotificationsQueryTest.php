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
    public function testEmailNotificationsNoAuth(): void
    {
        $this->graphQL('
             {
                emailNotifications
                 {
                    data {
                        id
                    }
                }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryEmailNotification(): void
    {
        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'group_type_id' => $email_notification[0]->group_type_id,
                    'group_role_id' =>  $email_notification[0]->group_role_id,
                    'company_id' => $email_notification[0]->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'emailNotification' => [
                    'id' => (string) $email_notification[0]->id,
                ],
            ],
        ]);
    }

    public function testQueryEmailNotificationsOrderBySort(): void
    {
        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    emailNotifications(orderBy: { column: ID, order: ASC }, first: 1) {
                        data {
                            id
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $email_notification->id,
            ],
        ]);
    }

    public function testQueryEmailNotificationsByGroupRoleId(): void
    {
        $email_notification = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query EmailNotifications ($id: Mixed) {
                    emailNotifications(filter: { column: HAS_GROUP_ROLE_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $email_notification->group_role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $email_notification->id,
            ],
        ]);
    }

    public function testQueryEmailNotificationsByCompanyId(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => $email->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'type' => (string) ucfirst($email->type),
            'recipient_type' => (string) strtoupper($email->recipient_type),
        ]);
    }

    public function testQueryEmailNotificationsByType(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'type' => $email->type,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'type' => (string) ucfirst($email->type),
            'recipient_type' => (string) strtoupper($email->recipient_type),
        ]);
    }

    public function testQueryEmailNotificationsByGroupType(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => $email->group_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'type' => (string) ucfirst($email->type),
            'recipient_type' => (string) strtoupper($email->recipient_type),
        ]);
    }

    public function testQueryEmailNotificationsBySubject(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $subject = DB::connection('pgsql_test')
            ->table('email_templates')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'subject' => $subject->subject,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'type' => (string) ucfirst($email->type),
            'recipient_type' => (string) strtoupper($email->recipient_type),
        ]);
    }

    public function testQueryEmailNotificationsByRecipientType(): void
    {
        $email = DB::connection('pgsql_test')
            ->table('email_notifications')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query EmailNotifications($recipient_type: Mixed) {
                    emailNotifications(
                        filter: {
                            column: RECIPIENT_TYPE
                            value: $recipient_type
                        }
                    ) {
                        data {
                            id
                            type
                            recipient_type
                        }
                    }
                }',
                'variables' => [
                    'recipient_type' => $email->recipient_type,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $email->id,
            'type' => (string) ucfirst($email->type),
            'recipient_type' => (string) strtoupper($email->recipient_type),
        ]);
    }
}
