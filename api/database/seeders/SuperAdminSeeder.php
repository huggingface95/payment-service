<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Role $role */
        $role = Role::query()->findOrFail(Role::SUPER_ADMIN_ID);

        $role->permissions()->sync(Permissions::all()->pluck('id'));
    }
}
