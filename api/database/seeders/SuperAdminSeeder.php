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
        if ($role = Role::query()->find(Role::SUPER_ADMIN_ID)) {
            $role->update([
                'name' => 'superadmin',
                'description' => 'Superadmin role',
                'company_id' => 1,
                'group_type_id' => 1,
            ]);
        } else {
            $role = Role::create([
                'id' => Role::SUPER_ADMIN_ID,
                'name' => 'superadmin',
                'description' => 'Superadmin role',
                'company_id' => 1,
                'group_type_id' => 1,
            ]);
        }
        $role->permissions()->sync(Permissions::all()->pluck('id'));
    }
}
