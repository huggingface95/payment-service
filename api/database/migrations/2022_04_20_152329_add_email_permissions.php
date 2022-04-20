<?php

use App\Enums\GuardEnum;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;

class AddEmailPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $permissions = [
            'Administration Module' => [
                'Email Templates:Tag' => [
                    [
                        'display_name' => 'Common',
                        'name' => 'Email Templates:Tag.Common',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'System',
                        'name' => 'Email Templates:Tag.System',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Admin Notify',
                        'name' => 'Email Templates:Tag.Admin Notify',
                        'type' => 'info',
                    ],
                ],
                'Email Templates:Settings' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Email Templates:Settings.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Email Templates:Settings.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Delete',
                        'name' => 'Email Templates:Settings.Delete',
                        'type' => 'important',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Email Templates:Settings.Add New',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Type Notification: Admin',
                        'name' => 'Email Templates:Settings.Type Notification: Admin',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Type Notification: Applicant',
                        'name' => 'Email Templates:Settings.Type Notification: Applicant',
                        'type' => 'info',
                    ],
                ],
                'Email Templates:Notifications' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Email Templates:Notifications.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Email Templates:Notifications.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Recipient Type:Group',
                        'name' => 'Email Templates:Notifications.Recipient Type:Group',
                        'type' => 'info',
                    ],
                    [
                        'display_name' => 'Recipient Type:Person',
                        'name' => 'Email Templates:Notifications.Recipient Type:Person',
                        'type' => 'info',
                    ],
                ],
            ],
            'Banking Module' => [
                'Email Templates:Tag' => [
                    [
                        'display_name' => 'Banking',
                        'name' => 'Email Templates:Tag.Banking',
                        'type' => 'info',
                    ],
                ],
            ]
        ];

        foreach ($permissions as $moduleName => $permissionsListNames) {
            $module = PermissionCategory::whereName($moduleName)->first();

            foreach ($permissionsListNames as $listName => $permissions) {
                $list = PermissionsList::whereName($listName)->where('permission_group_id', $module->id)->first();
                foreach ($permissions as $p) {
                    $p['guard_name'] = GuardEnum::GUARD_NAME;
                    $p['permission_list_id'] = $list->id;
                    Permissions::create($p);
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
