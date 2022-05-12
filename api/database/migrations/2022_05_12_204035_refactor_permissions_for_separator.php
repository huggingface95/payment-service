<?php

use App\Models\PermissionsList;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RefactorPermissionsForSeparator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PermissionsList::query()->where('type', 'individual')->update(['separator' => PermissionsList::PRIVATE]);

        $list = PermissionsList::query()->with('permissions')->where('type', 'individual')->where('separator', PermissionsList::PRIVATE)->get();

        foreach ($list as $item) {
            $newItem = $item->replicate();
            $newItem->separator = PermissionsList::BUSINESS;
            if ($newItem->save()) {
                $permissions = $item->permissions;
                foreach ($permissions as $permission) {
                    $newPermission = $permission->replicate();
                    $newPermission->permission_list_id = $newItem->id;
                    if ($newPermission->save()) {
                        $binds = DB::table('permission_operations_binds')->where('permission_id', $permission->id)->get();
                        $parents = DB::table('permission_operations_parents')->where('permission_id', $permission->id)->get();

                        foreach ($binds as $bind) {
                            DB::table('permission_operations_binds')
                                ->insert(['permission_id' => $newPermission->id, 'permission_operations_id' => $bind->permission_operations_id]);
                        }

                        foreach ($parents as $parent) {
                            DB::table('permission_operations_binds')
                                ->insert(['permission_id' => $newPermission->id, 'permission_operations_id' => $parent->permission_operations_id]);
                        }
                    }
                    else{
                        exit('Error');
                    }
                }
            }
            else{
                exit('Error');
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
