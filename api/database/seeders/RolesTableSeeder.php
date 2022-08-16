<?php

namespace Database\Seeders;

use App\Models\Members;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'api',
            'guard_name' => 'api',
            'description' => 'api role',
            'company_id' => 1
        ]);
    }
}
