<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberAccessLimitationsGroupRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_access_limitation_group_roles')->insert([
            'access_limitation_id' => 1,
            'group_role_id' => 2,
        ]);
    }
}
