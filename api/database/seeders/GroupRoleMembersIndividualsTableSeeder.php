<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupRoleMembersIndividualsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = DB::table('group_role_members_individuals')->where(['group_role_id' => 3, 'user_id' => 2])->first();
        if (!$row) {
            DB::table('group_role_members_individuals')->insert(['group_role_id' => 3, 'user_id' => 2]);
        }

        $row = DB::table('group_role_members_individuals')->where(['group_role_id' => 2, 'user_id' => 3])->first();
        if (!$row) {
            DB::table('group_role_members_individuals')->insert(['group_role_id' => 2, 'user_id' => 3]);
        }
    }
}
