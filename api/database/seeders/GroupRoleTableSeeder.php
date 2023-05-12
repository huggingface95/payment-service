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
        $groupRoles = [
            [
                'name' => 'Super Admin Group',
                'group_type_id' => 1,
                'role_id' => 35,
                'company_id' => 1,
                'description' => 'Test description 2',
                'module_id' => 2,
            ],
            [
                'name' => 'TestGroup',
                'group_type_id' => 1,
                'role_id' => 2,
                'company_id' => 1,
                'description' => 'Test description 1',
                'module_id' => 2,
            ],
            [
                'name' => 'Test',
                'group_type_id' => 1,
                'role_id' => 3,
                'company_id' => 1,
                'description' => 'Test description 1',
                'module_id' => 2,
            ],
            [
                'name' => 'Test Role 2',
                'group_type_id' => 1,
                'role_id' => 1,
                'company_id' => 1,
                'description' => 'Test description Role 2',
                'module_id' => 2,
            ],
            [
                'name' => 'Company',
                'group_type_id' => 2,
                'role_id' => 3,
                'company_id' => 1,
                'description' => 'Test description Role 2',
                'module_id' => 2,
            ],
            [
                'name' => 'Individual',
                'group_type_id' => 3,
                'role_id' => 4,
                'company_id' => 1,
                'description' => 'Test description Role 2',
                'module_id' => 2,
            ],
        ];

        $i = 1;
        foreach ($groupRoles as $group) {
            GroupRole::firstOrCreate([
                'id' => $i,
            ], $group);

            $i++;
        }
    }
}
