<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRoleUser;
use App\Models\Members;
use Illuminate\Database\Seeder;

class GroupRoleMembersIndividualsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 1,
            'user_id' => 1,
            'user_type' => class_basename(Members::class),
        ]);
        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 2,
            'user_id' => 2,
            'user_type' => class_basename(Members::class),
        ]);
        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 3,
            'user_id' => 3,
            'user_type' => class_basename(Members::class),
        ]);
        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 4,
            'user_id' => 4,
            'user_type' => class_basename(Members::class),
        ]);
        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 5,
            'user_id' => 1,
            'user_type' => class_basename(ApplicantCompany::class),
        ]);
        GroupRoleUser::query()->firstOrCreate([
            'group_role_id' => 6,
            'user_id' => 1,
            'user_type' => class_basename(ApplicantIndividual::class),
        ]);
    }
}
