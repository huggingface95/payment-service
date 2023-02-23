<?php

namespace Database\Seeders;

use App\Enums\EmailVerificationStatusEnum;
use App\Enums\MemberStatusEnum;
use App\Models\Members;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = [
            1 => [
                'last_name' => 'Member1 Last',
                'email' => 'test0@test.com',
                'password_hash' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'password_salt' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'company_id' => 2,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
                'department_id' => 1,
                'email_verification' => EmailVerificationStatusEnum::VERIFIED,
                'member_status_id' => MemberStatusEnum::ACTIVE,
                'last_login_at' => Carbon::now(),
            ],
            [
                'last_name' => 'Member2 Last',
                'email' => 'test@test.com',
                'password_hash' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'password_salt' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'company_id' => 1,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
                'department_id' => 2,
                'email_verification' => EmailVerificationStatusEnum::VERIFIED,
                'member_status_id' => MemberStatusEnum::ACTIVE,
                'last_login_at' => Carbon::now(),
            ],
            [
                'last_name' => 'Member3 Last',
                'email' => 'test2@test.com',
                'password_hash' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'password_salt' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'company_id' => 1,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
                'department_id' => 3,
                'email_verification' => EmailVerificationStatusEnum::VERIFIED,
                'member_status_id' => MemberStatusEnum::ACTIVE,
                'last_login_at' => Carbon::now(),
            ],
            [
                'last_name' => 'Member4 Last',
                'email' => 'superadmin@test.com',
                'password_hash' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'password_salt' => Hash::make(env('DEFAULT_PASSWORD', '1234567Qa')),
                'company_id' => 2,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
                'department_id' => 4,
                'email_verification' => EmailVerificationStatusEnum::VERIFIED,
                'member_status_id' => MemberStatusEnum::ACTIVE,
                'last_login_at' => Carbon::now(),
            ],
        ];

        foreach ($members as $id => $member) {
            Members::firstOrCreate([
                'id' => $id,
                'first_name' => 'Member'.$id,
            ], $member);
        }
    }
}
