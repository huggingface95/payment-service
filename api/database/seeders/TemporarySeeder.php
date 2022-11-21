<?php

namespace Database\Seeders;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\GroupRole;
use App\Models\GroupRoleUser;
use App\Models\Members;
use Exception;
use Illuminate\Database\Seeder;

class TemporarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        $groupRoleUsers = GroupRoleUser::query()->get();

        foreach ($groupRoleUsers as $groupRoleUser) {
            $groupRole = GroupRole::find($groupRoleUser->group_role_id);
            if ($groupRole->group_type_id == GroupRole::MEMBER) {
                $groupRoleUser->user_type = class_basename(Members::class);
            } elseif ($groupRole->group_type_id == GroupRole::COMPANY) {
                $groupRoleUser->user_type = class_basename(ApplicantCompany::class);
            } else {
                $groupRoleUser->user_type = class_basename(ApplicantIndividual::class);
            }
            $groupRoleUser->save();
        }
    }
}
