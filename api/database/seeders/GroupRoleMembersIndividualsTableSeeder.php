<?php

namespace Database\Seeders;

use App\Models\GroupRole;
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
        $groupRole = GroupRole::select('*')->where('id', 1)->get();
        DB::table('group_role_members_individuals')->insert(['group_role_id' => $groupRole[0]->id, 'user_id' => 2]);
    }
}
