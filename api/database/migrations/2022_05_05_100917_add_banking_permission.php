<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PermissionsList;
use App\Models\Permissions;
use App\Enums\GuardEnum;

class AddBankingPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionList = PermissionsList::whereName('Account Details')->first();

        Permissions::create([
            'guard_name' => GuardEnum::GUARD_NAME,
            'permission_list_id' => $permissionList->id,
            'name' => 'Account Details.Limits',
            'display_name' => 'Limits',
            'type'=>'info'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permissions::where('name','Account Details.Limits')->delete();
    }
}
