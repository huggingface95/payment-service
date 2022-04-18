<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PermissionCategory;
use App\Models\PermissionsList;

class InitPermissionsListData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionCategoryArray = ['Management Module','Settings Module','Administration Module','Banking Module'];
        foreach ($permissionCategoryArray as $permissionCategoryItem) {
            $permissionCategory = PermissionCategory::create([
                'name' => $permissionCategoryItem
            ]);
            PermissionsList::create([
                'permission_group_id'=>$permissionCategory->id
            ]);
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

