<?php

use App\Enums\GuardEnum;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPermissionsSmtpData extends Migration
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
                'Email Templates:SMTP Details' => [
                    [
                        'display_name' => 'Read',
                        'name' => 'Email Templates:SMTP Details.Read',
                        'type' => 'read',
                    ],
                    [
                        'display_name' => 'Edit',
                        'name' => 'Email Templates:SMTP Details.Edit',
                        'type' => 'edit',
                    ],
                    [
                        'display_name' => 'Add New',
                        'name' => 'Email Templates:SMTP Details.Add New',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Send test email',
                        'name' => 'Email Templates:SMTP Details.Send test email',
                        'type' => 'info',
                    ],
                ],
                'Email Templates:Notifications' => [
                    [
                        'display_name' => 'Banking',
                        'name' => 'Email Templates:Notifications.Banking',
                        'type' => 'info',
                    ],
                ],

            ],
        ];

        foreach ($permissions as $moduleName => $permissionsListNames) {
            $module = PermissionCategory::whereName($moduleName)->first();

            foreach ($permissionsListNames as $listName => $permissions) {
                $list = PermissionsList::firstOrCreate([
                    'type' => 'member',
                    'permission_group_id' => $module->id,
                    'name' => $listName,
                ]);

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
