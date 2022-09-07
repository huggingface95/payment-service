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
        Role::insert([
            'id'=>2,
            'name' => 'Super Role',
            'guard_name' => GuardEnum::GUARD_NAME,
            'description' => 'api role',
            'company_id' => 1,
            'group_type_id' => 1,
        ]);
    }
}
