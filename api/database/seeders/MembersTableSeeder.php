<?php

namespace Database\Seeders;

use App\Models\Members;
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
            [
                'last_name' => 'Member2 Last',
                'email' => 'test@test.com',
                'password_hash' => Hash::make('1234567Qa'),
                'password_salt' => Hash::make('1234567Qa'),
                'company_id' => 2,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
            ], [
                'last_name' => 'Member3 Last',
                'email' => 'test3@test.com',
                'password_hash' => Hash::make('1234567Qa'),
                'password_salt' => Hash::make('1234567Qa'),
                'company_id' => 2,
                'country_id' => 1,
                'language_id' => 2,
                'two_factor_auth_setting_id' => 1,
                'department_position_id' => 1,
            ],
        ];

        $i = 3;
        foreach ($members as $member) {
            Members::firstOrCreate([
                'id' => $i,
                'first_name' => 'Member' . $i,
            ], $member);

            $i++;
        }
    }
}
