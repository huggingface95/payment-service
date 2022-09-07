<?php

namespace Database\Seeders;

use App\Models\GroupRole;
use Illuminate\Database\Seeder;

class GroupRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupRole::insert([
            [
                'name' => 'TestGroup',
                'group_type_id' => 1,
                'role_id' => 2,
                'company_id' => 1,
            ], [
                'name' => 'Test',
                'group_type_id' => 1,
                'role_id' => 3,
                'company_id' => 1,
            ],
        ]);
    }
}
