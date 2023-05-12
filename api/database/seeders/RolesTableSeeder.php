<?php

namespace Database\Seeders;

use App\Enums\GuardEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Test Role 1',
                'guard_name' => GuardEnum::GUARD_NAME,
                'description' => 'api role',
                'company_id' => 1,
                'group_type_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Super Role',
                'guard_name' => GuardEnum::GUARD_NAME,
                'description' => 'api role',
                'company_id' => 1,
                'group_type_id' => 1,
            ], [
                'id' => 3,
                'name' => 'Company Role',
                'guard_name' => GuardEnum::GUARD_NAME,
                'description' => 'api role',
                'company_id' => 1,
                'group_type_id' => 2,
            ],
            [
                'id' => 4,
                'name' => 'Individual Role',
                'guard_name' => GuardEnum::GUARD_NAME,
                'description' => 'api role',
                'company_id' => 1,
                'group_type_id' => 3,
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'id' => $role['id'],
            ], $role);
        }
    }
}
