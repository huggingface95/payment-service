<?php

use App\Enums\GuardEnum;
use App\Models\PermissionCategory;
use App\Models\Permissions;
use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsDataPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'Banking Module' => [
                'Make Payments' => [
                    [
                        'display_name' => 'Create Payments',
                        'name' => 'Make Payments.Create Payments',
                        'type' => 'add',
                    ],
                    [
                        'display_name' => 'Sign Payments',
                        'name' => 'Make Payments.Sign Payments',
                        'type' => 'add',
                    ],
                ],
            ],
        ];

        $types = ['individual', 'member'];

        foreach ($permissions as $moduleName => $permissionsListNames) {
            $module = PermissionCategory::whereName($moduleName)->first();

            foreach ($permissionsListNames as $listName => $permissions) {
                foreach ($types as $type){
                    $list = PermissionsList::firstOrCreate([
                        'type' => $type,
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
