<?php

namespace Database\Seeders;

use App\Models\GroupRoleProvider;
use Illuminate\Database\Seeder;

class GroupRoleProvidersTableSeeder extends Seeder
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
                'group_role_id' => 1,
                'payment_provider_id' => 1,
                'commission_template_id' => 1,
                'is_default' => true,
            ],
            [
                'group_role_id' => 2,
                'payment_provider_id' => 1,
                'commission_template_id' => 1,
                'is_default' => true,
            ], [
                'group_role_id' => 3,
                'payment_provider_id' => 1,
                'commission_template_id' => 1,
                'is_default' => true,
            ],
        ];

        $i = 1;
        foreach ($groupRoles as $group) {
            GroupRoleProvider::firstOrCreate([
                'id' => $i,
            ], $group);

            $i++;
        }
    }
}
